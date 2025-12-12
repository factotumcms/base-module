<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Wave8\Factotum\Base\Builders\UserQueryBuilder;
use Wave8\Factotum\Base\Contracts\NotifiableInterface;
use Wave8\Factotum\Base\Policies\UserPolicy;

#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable implements NotifiableInterface
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
    ];

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the settings associated with the user.
     *
     * @return BelongsToMany A relation linking the user to its `Setting` models.
     */
    public function settings(): BelongsToMany
    {
        return $this->belongsToMany(Setting::class);
    }

    /**
     * Get the user's profile picture media relation.
     *
     * @return BelongsTo The HasOne relation to the Media model representing the user's profile picture.
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'avatar_id');
    }

    public function passwordHistories(): HasMany
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function isCurrentPasswordExpired(): bool
    {
        if (
            $this->last_login_at === null ||
            $this->passwordHistories()->count() === 0 ||
            ! hash_equals($this->password, $this->passwordHistories()->latest()->firstOrFail()->password)
        ) {
            return false;
        }

        return $this->passwordHistories()->latest()
            ->firstOrFail()->expires_at->lessThanOrEqualTo(now());
    }
}
