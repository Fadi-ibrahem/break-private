<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\AttendanceLog;

class AttendService
{

    public static function processEmployeeLog(int $employeeCode, Carbon $logDate)
    {

        $lastEmployeeLog = AttendanceLog::where(['code' => $employeeCode])
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEmployeeLog) {
            $checkinDate = $lastEmployeeLog->check_in_at;

            $maxShiftDate =  $checkinDate->copy()->addHour(14);

            if ($maxShiftDate->gte($logDate) && $checkinDate->lte($logDate)) {
                // still in the shift
                $totalShiftMinutes = $logDate->diffInSeconds($checkinDate);
                $lastEmployeeLog->update([
                    'total_shift_minutes' => floor($totalShiftMinutes / 60),
                    'check_out_at' => $logDate->toDateTimeString()
                ]);
                return;
            }
        }

        // create new log
        AttendanceLog::create([
            'code' => $employeeCode,
            'check_in_at' => $logDate->toDateTimeString()
        ]);
    }
}
