<?php

namespace App\Models;

use App\Models\Concerns\HasOwner;
use App\Models\Concerns\IsOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $name
 * @property string|null $description
 * @property-read Collection<int, CommunityMember> $members
 * @property-read Collection<int, CommunityInvitation> $invitations
 * @property-read Collection<int, User> $memberUsers
 */
class Community extends Model implements IsOwned
{
    use HasFactory;
    use HasOwner;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    /**
     * @return HasMany<CommunityMember>
     */
    public function members(): HasMany
    {
        return $this->hasMany(CommunityMember::class);
    }

    /**
     * @return HasMany<CommunityInvitation>
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(CommunityInvitation::class);
    }

    /**
     * @return HasManyThrough<User>
     */
    public function memberUsers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, CommunityMember::class);
    }

    public function addMember(User $user): CommunityMember
    {
        return $this->members()->create([
            'user_id' => $user->id,
        ]);
    }

    public function addMemberByEmail(string $email): CommunityMember|CommunityInvitation
    {
        if ($user = User::where('email', $email)->first()) {
            return $this->addMember($user);
        }

        return $this->invitations()->firstOrCreate([
            'email' => $email,
        ]);
    }

    public function isMember(User $user): bool
    {
        return $this->members->contains(function (CommunityMember $member) use ($user) {
            return $member->isUser($user);
        });
    }
}
