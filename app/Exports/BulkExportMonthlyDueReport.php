<?php

namespace App\Exports;

use App\Exports\Bulk;
use Auth;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BulkExportMonthlyDueReport implements FromQuery,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // use Exportable;

    public function headings(): array
    {
        return [
            'Id',
            'name',
            'email',
            'createdAt',
            'updatedAt',
        ];
    }
    public function query()
    {
        $school_id=Auth::user()->school_id;
        return Invoice::query()->where('invoice.school_id','=',$school_id)
            ->where('invoice.status','=',0)
            /*->whereMonth('month',date('m'))*/;
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($bulk): array
    {
        return [
            $bulk->id,
            $bulk->name,
            $bulk->email,
            Date::dateTimeToExcel($bulk->created_at),
            Date::dateTimeToExcel($bulk->updated_at),
        ];
    }

}
