<?php

namespace App\Models;

use App\Models\Concerns\IsOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int|null $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection<int, Community> $ownedCommunities
 * @property Collection<int, Member> $memberships
 * @property Collection<int, Community> $communities
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
     * @return HasMany<Community>
     */
    public function ownedCommunities(): HasMany
    {
        return $this->hasMany(Community::class, 'owner_id');
    }

    /**
     * @return HasMany<Member>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * @return HasMany<Community>
     */
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'members')
            ->as('membership');
    }

    public function owns(IsOwned $model): bool
    {
        return $model->isOwnedBy($this);
    }
}
