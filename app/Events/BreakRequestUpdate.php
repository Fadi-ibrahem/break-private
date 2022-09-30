<?php

namespace App\Events;

use App\Models\BreakModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BreakRequestUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $break;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BreakModel $break)
    {
        $this->break = $break;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('users.' . $this->break->employee_id);
    }

    public function broadcastWith()
    {
        return [
            "id" => $this->break->id,
            "approved" => $this->break->is_approved,
            'timestamp' => $this->break->is_approved ? $this->break->start_time->timestamp : null,
            "html" => view('site.breaks._break-row', ['break' => $this->break])->render(),
        ];
    }
}
