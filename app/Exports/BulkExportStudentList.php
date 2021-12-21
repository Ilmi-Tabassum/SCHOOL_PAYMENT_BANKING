<?php

namespace App\Exports;

use App\Exports\Bulk;
use App\Models\Student;
use Auth;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BulkExportStudentList implements FromQuery,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // use Exportable;

    public function headings(): array
    {
        return [
            'Id'
        ];
    }
    public function query()
    {
        if(Auth::user()->school_id)
        {
            $school_id=Auth::user()->school_id;
            $c = Student::Join('student_academics', function($join) {
                $join->on('students.id', '=', 'student_academics.student_id');
            })->where('student_academics.school_id', '=',$school_id);
            }else{
            $c = Student::Join('student_academics', function($join) {
                $join->on('students.id', '=', 'student_academics.student_id');
            });
        }

        //return Invoice::query()->select('id','invoice_no');
        return $c;
       // return Student::query()->where('students.school_id','=',$school_id)
            /*->whereMonth('month',date('m'))*/
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
