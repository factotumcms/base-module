<?php

namespace Wave8\Factotum\Base\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Wave8\Factotum\Base\Policies\UserPolicy;

#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasPermissions;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    protected string $guard_name = 'web';

    protected function getDefaultGuardName(): string
    {
        return $this->guard_name;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the settings associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany A relation linking the user to its `Setting` models.
     */
    public function settings(): BelongsToMany
    {
        return $this->belongsToMany(Setting::class);
    }

    /**
     * Get the user's profile picture media relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne The HasOne relation to the Media model representing the user's profile picture.
     */
    public function profile_picture(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }
}