<?php

namespace App\Models;

use App\Models\Concerns\HasOwner;
use App\Models\Concerns\IsOwned;
use Database\Factories\CommunityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $name
 * @property string|null $description
 * @property-read Collection<int, CommunityUser> $members
 * @property-read Collection<int, CommunityInvitation> $invitations
 * @property-read Collection<int, User> $memberUsers
 *
 * @method static CommunityFactory factory()
 */
class Community extends Model implements IsOwned
{
    use HasFactory;
    use HasOwner;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    /**
     * @return HasMany<CommunityInvitation>
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(CommunityInvitation::class);
    }

    /**
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('communityMembership')
            ->using(CommunityUser::class);
    }

    public function isMember(User $user): bool
    {
        return $this->users->where('id', $user->id)->isNotEmpty();
    }
}
