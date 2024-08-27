<?php

namespace App\Events\Transaction;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Transaction $transaction;
    public array $data;

    /**
     * Create a new event instance.
     */
    public function __construct(Transaction $transaction, array $data)
    {
        $this->transaction = $transaction;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
