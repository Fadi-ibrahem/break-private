<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use App\Models\AttendanceLog;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendancesExport implements FromCollection, Responsable, WithHeadings, WithMapping
{

    use Exportable;

    public function headings(): array
    {
        return [
            'date',
            'name',
            'code',
            'check_in',
            'check_out',
            'shift_time'
        ];
    }

    public function map($log): array
    {
        return [
            $log->check_in_at->toDateString(),
            $log->name,
            $log->code,
            $log->check_in_at->format('g:i A'),
            $log->check_out_at ? $log->check_out_at->format('g:i A') : '',
            (($log->total_shift_minutes / 60) - (($log->total_shift_minutes % 60) / 60)) . " h : " . $log->total_shift_minutes % 60 . " m"
        ];
    }

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = 'attendance_log.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return AttendanceLog::getLogs();
    }
}
