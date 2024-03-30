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
 * @property-read Collection<int, Member> $members
 * @property-read Collection<int, User> $memberUsers
 */
class Community extends Model implements IsOwned
{
    use HasFactory;
    use HasOwner;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    /**
     * @return HasMany<Member>
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * @return HasManyThrough<User>
     */
    public function memberUsers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Member::class);
    }

    public function addMember(User $user): Member
    {
        return $this->members()->create([
            'user_id' => $user->id,
        ]);
    }

    public function isMember(User $user): bool
    {
        return $this->members->contains(function (Member $member) use ($user) {
            return $member->isUser($user);
        });
    }
}
