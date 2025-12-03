<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ConfirmSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriberFactory> */
    use HasFactory, HasTags, Notifiable;

    protected $casts = [
        'confirmed_at' => 'datetime',
        'confirmation_sent_at' => 'datetime',
    ];

    public function needsConfirmation() : bool
    {
        return null === $this->confirmed_at;
    }

    public function tokenMatches(?string $token) : bool
    {
        if (null === $token || null === $this->confirmation_token) {
            return false;
        }

        return hash_equals($this->confirmation_token, hash('sha256', $token));
    }

    public function markAsConfirmed() : void
    {
        $this->forceFill([
            'confirmed_at' => now(),
            'confirmation_token' => null,
        ])->save();
    }

    public function sendConfirmationNotification() : void
    {
        $token = $this->generateConfirmationToken();

        $this->notify(new ConfirmSubscription($token));
    }

    protected function generateConfirmationToken() : string
    {
        $token = Str::random(40);

        $this->forceFill([
            'confirmation_token' => hash('sha256', $token),
            'confirmation_sent_at' => now(),
        ])->save();

        return $token;
    }
}
