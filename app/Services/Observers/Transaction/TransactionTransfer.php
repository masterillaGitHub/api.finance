<?php
declare(strict_types=1);

namespace App\Services\Observers\Transaction;

use App\Enums\TransactionType;
use App\Models\Transaction;

final readonly class TransactionTransfer
{
    public function __construct(
        private Transaction $transaction
    )
    {
    }

    public function restore(): void
    {
        /** @var Transaction $transferTransaction */
        $transferTransaction = Transaction::onlyTrashed()->find($this->transaction->transfer_transaction_id);

        $transferTransaction?->restore();
    }

    /**
     * Спеціальне видалення під час оновлення основної транзакції. Щоб додаткова транзакція не видаляла свою
     * додаткову транзакцію (яка являється основною), бо виходить ланцюгова реакція.
     * При видаленні перевіряється властивість "is_transfer" якщо є, то видалення не відбувається, але
     * спрацьовує інший код який знаходиться в події видалення
     */
    public function removeByUpdate(): void
    {
        $transferTransaction = $this->getTransferTransaction();

        if ($transferTransaction) {
            $transferTransaction->is_transfer = true;

            $transferTransaction->delete();
        }
    }

    public function remove(): void
    {
        $transferTransaction = $this->getTransferTransaction();

        if (empty($this->transaction->is_transfer) && $transferTransaction) {
            $transferTransaction->delete();
        }
    }

    public function isSwitchedToTransfer(): bool
    {
        return $this->isTypeSwitchedToTransfer();
    }

    public function isSwitchedToNotTransfer(): bool
    {
        return $this->isTypeSwitchedToNotTransfer();
    }

    public function hasTransfer(): bool
    {
        return !empty($this->transaction->transfer_transaction_id);
    }

    public function isTransfer(): bool
    {
        return $this->transaction->type_id === TransactionType::TRANSFER->value;
    }

    public function isTypeSwitchedToNotTransfer(): bool
    {
        return $this->transaction->getOriginal('type_id') === TransactionType::TRANSFER->value
            && $this->transaction->getAttribute('type_id') !== TransactionType::TRANSFER->value;
    }

    public function isTypeSwitchedToTransfer(): bool
    {
        return $this->transaction->getOriginal('type_id') !== TransactionType::TRANSFER->value
            && $this->transaction->getAttribute('type_id') === TransactionType::TRANSFER->value;
    }

    private function getTransferTransaction(): ?Transaction
    {
        return Transaction::find($this->transaction->transfer_transaction_id);
    }
}
