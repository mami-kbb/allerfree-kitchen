<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'reading',
        'category',
    ];

    public function recipes() {
        return $this->belongsToMany(Recipe::class, 'ingredient_recipe')->withPivot('quantity');
    }

    public function allergyCategories() {
        return $this->belongsToMany(AllergyCategory::class, 'allergy_category_ingredient');
    }
}
