<?php

namespace Velia\Common\Traits\Belongs;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ToUser
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, constant('self::USER_FOREIGN_KEY'));
    }

    /**
     * @param Builder $query
     * @param User $user
     *
     * @return void
     */
    public function scopeByUser(Builder $query, User $user): void
    {
        $query->where(constant('self::USER_FOREIGN_KEY'), $user->id);
    }

    /**
     * @param Builder $query
     * @param int $id
     *
     * @return void
     */
    public function scopeByUserId(Builder $query, int $id): void
    {
        $query->where(constant('self::USER_FOREIGN_KEY'), $id);
    }
}
