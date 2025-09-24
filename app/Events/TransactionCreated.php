<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TransactionCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn(): Channel
    {
        // Each user listens to their private channel
        // return new PrivateChannel('transactions.' . $this->transaction->user->account_ref);
        return new Channel('transactions');
    }

    public function broadcastAs(): string
    {
        return 'TransactionCreated';
    }

    public function broadcastWith(): array
    {
        return [
            'transaction' => [
                'accountRef' => $this->transaction->user->account_ref,
                'type' => $this->transaction->type,
                'amount' => $this->transaction->amount,
                'narration' => $this->transaction->narration,
                'reference' => $this->transaction->reference,
                'isCompleted' => $this->transaction->is_completed,
                'createdAt' => $this->transaction->created_at,
            ]
        ];
    }
}
