<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MonthlyFeeDeducted implements ShouldBroadcastNow
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
