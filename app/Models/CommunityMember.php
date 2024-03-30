<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int|null $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $user_id
 * @property int|null $community_id
 * @property-read Community|null $community
 */
class CommunityMember extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'community_id'];

    protected $hidden = ['confidentialUser'];

    public function user(): void
    {
        throw new Exception(
            'You should not access user information directly from the community member. '
        );
    }

    /**
     * @return HasOne<User>
     */
    public function confidentialUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function preferredName(): string
    {
        return $this->confidentialUser->name ?? 'Unknown';
    }

    public function preferredContactEmail(): string
    {
        return $this->confidentialUser->email ?? 'Unknown';
    }

    /**
     * @return BelongsTo<Community>
     */
    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function isUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
