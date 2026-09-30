<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;

class SendVerificationEmailSafely
{
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if (! ($user instanceof MustVerifyEmail) || $user->hasVerifiedEmail()) {
            return;
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('認証メールの送信に失敗しました', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}