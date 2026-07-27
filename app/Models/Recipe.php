<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'approved_at',
        'rejection_reason',
    ];

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

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function scopeKeywordSearch($query, $keyword) {
        if (!empty($keyword)) {
            $keywords = preg_split('/\s+/', $keyword);

            foreach ($keywords as $word) {
                $query->where('name', 'LIKE', "%{$word}%");
            }
        }
        return $query;
    }

    public function scopeExcludeAllergies($query, $excludeAllergies) {
        if (!empty($excludeAllergies)) {
            $query->whereDosentHave('allergies', function ($q) use ($excludeAllergies) {
                $q->whereIn('allergies.id', $excludeAllergies);
            });
        }
        return $query;
    }

    public function scopeSearch($query, $keyword, $excludeAllergies)
    {
        return $query
        ->keywordSearch($keyword)
        ->excludeAllergies($excludeAllergies);
    }
}
