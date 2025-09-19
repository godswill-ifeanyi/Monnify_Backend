<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MonthlyFeeDeducted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    // Broadcast on private channel per user
    public function broadcastOn()
    {
        return new Channel('user');
    }

    public function broadcastAs()
    {
        return 'MonthlyFeeDeducted';
    }
}
