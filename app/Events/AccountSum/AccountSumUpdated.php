<?php

namespace App\Events\AccountSum;

use App\Models\AccountSum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountSumUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AccountSum $accountSum;
    public array $data;

    /**
     * Create a new event instance.
     */
    public function __construct(AccountSum $accountSum, array $data)
    {
        $this->accountSum = $accountSum;
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
