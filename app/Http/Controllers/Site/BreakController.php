<?php

namespace App\Http\Controllers\Site;

use Auth;
use Carbon\Carbon;
use App\Models\BreakModel;
use App\Services\Fop2Service;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BreakStoreRequest;

class BreakController extends Controller
{
    public function index()
    {
        $this->authorize('viewBreaks', BreakModel::class);

        $breaks = Auth::user()->breaks()->today()
            ->orderBy('id', 'desc')->get();

        $reasons = BreakModel::$reasons;
        $times = BreakModel::$times;
        return view('site.breaks.index', compact('breaks', 'reasons', 'times'));
    }

    public function store(BreakStoreRequest $request)
    {
        $this->authorize('create', BreakModel::class);

        Auth::user()->breaks()->create([
            "reason" => $request->reason,
            "time" => $request->time,
            "date" => Carbon::today()->toDateString()
        ]);

        return redirect()->route('breaks.index');
    }

    public function update()
    {
        $break = Auth::user()->breaks()->active()->first();
        if ($break) {
            $end_time = Carbon::now();

            $time = $end_time->diffInSeconds($break->start_time);

            $break->update([
                'end_time' => $end_time->toTimeString(),
                'actual_time' => floor($time / 60)
            ]);

            // update fop2 employee status
            if ($ext = $break->employee->extension) {
                App::terminating(fn () => Fop2Service::changeStatus($ext, "available"));
            }
        }

        return redirect()->route('breaks.index');
    }
}
