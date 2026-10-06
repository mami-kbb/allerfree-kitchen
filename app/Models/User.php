<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendEmailVerificationNotification() {
        try {
            $this->notify(new VerifyEmail);
        } catch (\Throwable $e) {
            // 送信に失敗しても、画面はエラーにしない。原因はログに残す
            Log::error('認証メールの送信に失敗しました', [
                'user_id' => $this->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function isAdmin() {
        return $this-> role === 'admin';
    }

    public function isUser() {
        return $this-> role === 'user';
    }

    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function allergies() {
        return $this->belongsToMany(Allergy::class, 'allergy_user');
    }

    public function recipes() {
        return $this->hasMany(Recipe::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likedRecipes() {
        return $this->belongsToMany(Recipe::class, 'likes')->withTimestamps();
    }

    public function allergyIds() {
        return $this->allergies->pluck('id')->toArray();
    }
}
