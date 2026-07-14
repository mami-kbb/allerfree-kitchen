<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'allergy_category_ingredient');
    }
}
