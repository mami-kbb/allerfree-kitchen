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
        return $this->hasMany(Step::class);
    }
    public function ingredients() {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe');
    }

    public function allergies() {
        return $this->belongsToMany(Allergy::class, 'allergy_recipe');
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
