<?php

namespace App\Policies;

use App\Enums\TransactionParentCategoryEnum;
use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Transaction $transaction): bool
    {
        $isSameUser = $user->id === $transaction->user_id;
        $isInitial = $transaction->category_id === TransactionParentCategoryEnum::INITIAL->value;

        return $isSameUser && !$isInitial;
    }
}
