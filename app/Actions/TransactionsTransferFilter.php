<?php
declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use Illuminate\Support\Collection;

final class TransactionsTransferFilter
{
    private array $removeTransactionsIds = [];

    public static function handle(Collection $transactions): Collection
    {
        $instance = new self();

        return $instance->filtering($transactions);
    }

    private function filtering(Collection $transactions): Collection
    {
        $transactions
            ->sortBy([
                ['transaction_at','desc'],
                ['id']
            ])
            ->each(
                fn(Transaction $transaction) => $this->handleDoubleTransferTransaction($transaction)
            );

        return $transactions->reject(fn(Transaction $transaction) => $this->isRejectTransaction($transaction));
    }

    private function handleDoubleTransferTransaction(Transaction $transaction): void
    {
        if ($transaction->getType() !== \App\Enums\TransactionType::TRANSFER) {
            return;
        }

        if (empty($transaction->transfer_transaction_id)) {
            return;
        }

        if (in_array($transaction->id, $this->removeTransactionsIds)) {
            return;
        }

        $this->removeTransactionsIds[] = $transaction->transfer_transaction_id;
    }


    private function isRejectTransaction(Transaction $transaction): bool
    {
        return in_array($transaction->id, $this->removeTransactionsIds);
    }
}
