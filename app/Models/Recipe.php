<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Override;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image',
        'description',
        'servings',
        'tips',
        'status',
        'approval_at',
        'rejection_reason',
    ];

    #[Override]
    protected static function booted()
    {
        static::deleting(function (Recipe $recipe) {
            if ($recipe->image && Storage::disk('public')->exists($recipe->image)) {
                Storage::disk('public')->delete($recipe->image);
            }
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function steps() {
        return $this->hasMany(Step::class)->orderBy('step_number');
    }
    public function ingredients() {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe')
        ->withPivot('quantity')
        ->withTimestamps();
    }

    public function allergies() {
        return $this->belongsToMany(Allergy::class, 'allergy_recipe')
        ->withTimestamps();
    }

    public function allergyIds() {
        return $this->allergies->pluck('id')->toArray();
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function scopeApproved($query) {
        return $query->where('status', 1);
    }

    public function scopeKeywordSearch($query, $keyword) {
        if (empty ($keyword)) {
            return $query;
        }

        //スペースで区切られたキーワードを配列にする
        $keywords = preg_split('/\s+/', trim($keyword));

        //レシピ名と材料名でAND検索をする。
        foreach ($keywords as $word) {
            $query->where(function ($query) use ($word) {
                $query->where('name', 'like', "%{$word}%")
                ->orWhereHas('ingredients', function ($q) use ($word) {
                    $q->where('ingredients.name', 'like', "%{$word}%");
                });
            });
        }

        return $query;
    }

    public function scopeExcludeIngredients($query, $excludeIngredients) {
        if (empty($excludeIngredients)) {
            return $query;
        }

        $words = preg_split('/\s+/', trim($excludeIngredients));

        foreach ($words as $word) {
            $query->whereDoesntHave('ingredients', function ($q) use ($word) {
                $q->where('ingredients.name', 'like', "%{$word}%");
            });
        }

        return $query;
    }

    public function scopeExcludeAllergies($query, $excludeAllergies) {
        if (!empty($excludeAllergies)) {
            $query->whereDoesntHave('allergies', function ($q) use ($excludeAllergies) {
                $q->whereIn('allergies.id', $excludeAllergies);
            });
        }
        return $query;
    }

    //allergy_categoriesとリレーションがあるのはingredients
    public function scopeExcludeCategories($query, $excludeCategories) {
        if (!empty($excludeCategories)) {
            $query->whereDoesntHave('ingredients', function ($q) use ($excludeCategories) {
                $q->whereHas('allergyCategories', function ($q2) use ($excludeCategories) {
                    $q2->whereIn('allergy_categories.id', $excludeCategories);
                });
            });
        }
        return $query;
    }

    public function scopeSearch($query, $keyword, $excludeIngredients, $excludeAllergies, $excludeCategories)
    {
        return $query
        ->keywordSearch($keyword)
        ->excludeIngredients($excludeIngredients)
        ->excludeAllergies($excludeAllergies)
        ->excludeCategories($excludeCategories);
    }
}
