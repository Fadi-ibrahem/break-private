<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\BreakModel;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class BreaksExport implements FromCollection, Responsable, WithHeadings, WithMapping
{

    use Exportable;

    public function headings(): array
    {
        return [
            'Date',
            'Name',
            'Code',
            'Request Start Time',
            'Request End Time',
            'Request Total Time(H:M:S)',
        ];
    }

    public function map($entry): array
    {
        // Start and end date
        $startTime = \Carbon\Carbon::parse($entry->start_time);
        $endTime = \Carbon\Carbon::parse($entry->end_time);    
        $diff = $endTime->diff($startTime);

        return [
            $entry->date,
            $entry->name,
            $entry->code,
            date("g:i A", strtotime($entry->start_time)),
            date("g:i A", strtotime($entry->end_time)),
            $diff->h . "h : " . $diff->i . "m : " . $diff->s . "s",
        ];
    }

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = 'break_reports.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return BreakModel::getReports();
    }
}
