<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $user_id
 * @property int|null $community_id
 * @property-read User|null $user
 * @property-read Community|null $community
 */
class Member extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'community_id'];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
