<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Support\Str;
use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Impersonate, MustVerifyEmail, Notifiable;

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted() : void
    {
        static::creating(
            fn (User $user) => $user->slug = Str::slug($user->name)
        );
    }

    /**
     * @return array<string, string>
     */
    protected function casts() : array
    {
        return [
            'github_data' => 'array',
            'password' => 'hashed',
            'refreshed_at' => 'datetime',
        ];
    }

    public function avatar() : Attribute
    {
        return Attribute::make(
            function (?string $value) {
                $avatar = $value
                    ?? data_get($this->github_data, 'user.avatar_url')
                    ?? data_get($this->github_data, 'avatar_url');

                if (! empty($avatar)) {
                    return $avatar;
                }

                return secure_asset('img/placeholder.png');
            },
        );
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class)->published();
    }

    public function links() : HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function about() : Attribute
    {
        return Attribute::make(
            fn () => $this->biography ?? $this->github_data['user']['bio'] ?? '',
        );
    }

    public function blogUrl() : Attribute
    {
        return Attribute::make(
            fn () => $this->github_data['user']['blog'] ?? null,
        );
    }

    public function company() : Attribute
    {
        return Attribute::make(
            fn () => $this->github_data['user']['company'] ?? null,
        );
    }

    public function isAdmin() : bool
    {
        return 'benjamincrozat' === $this->github_login;
    }

    public function canAccessPanel(Panel $panel) : bool
    {
        return $this->isAdmin();
    }
}
