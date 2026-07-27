<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'quantity'
    ];

    public function ingredient() {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }
}
