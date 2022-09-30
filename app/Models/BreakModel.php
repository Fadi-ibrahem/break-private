<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class BreakModel extends Model
{
    use HasFactory;

    public static $times = [5, 10, 15, 30];
    public static $reasons = ['wc', 'prayer', 'lunch', 'other', 'coffee', 'smoking'];

    protected $table = "breaks";

    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the user that owns the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }


    public static function getReports()
    {
        $entries = DB::table('breaks')->select('date', 'name', 'code', 'start_time', 'end_time', 'actual_time')
            ->join('users', 'users.id', '=', 'breaks.employee_id')->where('is_approved', 1)->whereNotNull('end_time');

        $code = request()->query('emp_code');
        $from = request()->query('from');
        $to = request()->query('to');

        if ($code) {
            $entries->where('users.code', $code);
        }

        if ($from) {
            $entries->where('date', '>=', $from);
        }

        if ($to) {
            $entries->where('date', '<=', $to);
        }

        if (!$from && !$to) {
            $today = Carbon::now()->toDateString();
            $entries->where('date', '=', $today);
        }
        if(auth()->user()->type != 'super_admin' && auth()->user()->type == 'supervisor') {
            $entries->where('users.supervisor_id', auth()->user()->id);
        }

        return $entries->get();
    }

    public function scopePending($query)
    {
        return $query->where('date', Carbon::today()->toDateString())
            ->whereNull('is_approved');
    }

    public static function getPendingRequestsCount($supervisor_id)
    {
        return self::pending()
            ->whereHas('employee', function ($query) use ($supervisor_id) {
                $query->where('supervisor_id', $supervisor_id);
            })->count();
    }

    public static function getPendingRequestsCountWithoutAssistant($supervisor_id)
    {
        return self::pending()
            ->whereHas('employee', function ($query) use ($supervisor_id) {
                $query->where('supervisor_id', $supervisor_id);
            })->where('employee_id', '!=', auth()->id())->count();
    }

    public function scopeActive($query)
    {
        return $query->where('date', Carbon::now()->toDateString())
            ->where('is_approved', 1)
            ->whereNull('end_time');
    }

    public function isActive()
    {
        return $this->is_approved && !$this->end_time;
    }

    public function scopeToday($query)
    {
        return $query->where('date', Carbon::now()->toDateString());
    }

}
