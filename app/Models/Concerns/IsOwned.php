<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface IsOwned
{
    /**
     * @return BelongsTo<User>
     */
    public function owner(): BelongsTo;

    public function isOwnedBy(User $user): bool;
}
