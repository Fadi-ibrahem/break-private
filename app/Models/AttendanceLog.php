<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected $dates = ['check_in_at', 'check_out_at'];

    /**
     * Get the user that owns the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'code');
    }

    /**
     * get all logs filtered by from_date, to_date, employee_code
     * 
     */
    public static function getLogs()
    {
        $logs  = self::query()->select(
            'users.name',
            'attendance_logs.code',
            'total_shift_minutes',
            'check_in_at',
            'check_out_at'
        )->join('users', 'attendance_logs.code', '=', 'users.code');

        $code = request()->query('emp_code');
        $from = request()->query('from');
        $to = request()->query('to');

        if ($code) {
            $logs->where(['attendance_logs.code' => $code]);
        }

        if ($from) {
            $logs->whereDate('check_in_at', '>=', $from);
        }

        if ($to) {
            $logs->whereDate('check_in_at', '<=', $to);
        }

        if (!$from && !$to) {
            $today = Carbon::now()->toDateString();
            $logs->whereDate('check_in_at', $today);
        }
        if(auth()->user()->type != 'super_admin' && auth()->user()->type == 'supervisor') {
            $logs->where('users.supervisor_id', auth()->user()->id);
        }


        return $logs->get();
    }
}
