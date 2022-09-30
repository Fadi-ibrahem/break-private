<?php

namespace App\Observers;

use App\Events\BreakEnded;
use App\Events\BreakRequestCurrent;
use App\Models\BreakModel;
use App\Events\NewBreakRequest;
use App\Events\BreakRequestUpdate;
use App\Events\BreakRequestHandled;
use Illuminate\Support\Facades\Log;

class BreakObserver
{
    /**
     * Handle the BreakModel "created" event.
     *
     * @param  \App\Models\BreakModel  $break
     * @return void
     */
    public function created(BreakModel $break)
    {

        try {
            $newBreakRequestObj = new NewBreakRequest($break);
            if (auth()->id() != $break->employee_id) {
                broadcast($newBreakRequestObj)->toOthers();
            } else {
                broadcast($newBreakRequestObj);
            }
        } catch (\Throwable $th) {
            // catch if websocket worker is offline
            Log::error($th);
        }
    }

    /**
     * Handle the BreakModel "updated" event.
     *
     * @param  \App\Models\BreakModel  $break
     * @return void
     */
    public function updated(BreakModel $break)
    {
        try {
            if ($break->end_time) {
                BreakEnded::dispatch($break);
            } else {
                BreakRequestUpdate::dispatch($break);
                
                $handleRequestObj = new BreakRequestHandled($break);
                $currentRequestObj = new BreakRequestCurrent($break);
                if (auth()->id() == $break->employee_id) {
                    broadcast($handleRequestObj)->toOthers();
                    broadcast($currentRequestObj)->toOthers();
                } else {
                    broadcast($handleRequestObj);
                    broadcast($currentRequestObj);
                }
            }
        } catch (\Throwable $th) {
            // catch if websocket worker is offline
            Log::error($th);
        }
    }
}
