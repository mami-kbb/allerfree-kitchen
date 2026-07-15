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
        'category',
    ];

    public function recipes() {
        return $this->belongsToMany(Recipe::class, 'ingredient_recipe');
    }

    public function allergyCategory() {
        return $this->belongsToMany(AllergyCategory::class, 'allergy_category_ingredient');
    }
}
