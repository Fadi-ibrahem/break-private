<?php

namespace App\Events;

use App\Models\BreakModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BreakRequestCurrent implements ShouldBroadcast
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
        return new PrivateChannel('supervisors.' . $this->break->employee->supervisor_id);
    }

    public function broadcastWith()
    {
        return [
            "id" => $this->break->id,
            'is_approved' => $this->break->is_approved,
            'html' => $this->break->is_approved ? view('admin.breaks._current-break-row', ['break' => $this->break])->render() : null,
        ];
    }
}
