<?php

namespace App\Exports;
use App\Models\Invoice;
use DB;
use Auth;
use App\Exports\Bulk;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BulkExport implements FromQuery,WithHeadings
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
/*            'email',
            'createdAt',
            'updatedAt',*/
        ];
    }
    public function query()
    {
        $c = Invoice::Join('bulk', function($join) {
            $join->on('invoice.id', '=', 'bulk.id');
        })->select('bulk.id','invoice_no');
        //return Invoice::query()->select('id','invoice_no');
        return $c;
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($bulk): array
    {
        return [
            $bulk->id,
            $bulk->invoice_no,
/*            $bulk->email,
            Date::dateTimeToExcel($bulk->created_at),
            Date::dateTimeToExcel($bulk->updated_at),*/
        ];
    }

}
