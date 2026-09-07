<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_image',
        'comment',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    protected function profileImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->profile_image ?: asset('images/icon.png')
        );
    }
}
