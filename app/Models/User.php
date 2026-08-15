<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Stores a GitHub user who can take part in the blog.
 *
 * GitHub sign-in and refresh jobs keep the profile data used for avatar and link
 * fallbacks. A user can own posts, submit links, write comments, and receive
 * queued email. Admin access belongs only to the site's fixed owner login; there
 * is no general role field.
 */
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, MustVerifyEmail, Notifiable;

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

    public function githubUrl() : Attribute
    {
        return Attribute::make(
            fn () => data_get($this->github_data, 'user.html_url')
                ?? 'https://github.com/' . $this->github_login,
        );
    }

    public function isAdmin() : bool
    {
        return 'benjamincrozat' === $this->github_login;
    }
}
