<?php
namespace App\Http\Controllers;
use App\Exports\BulkExportMonthlyDueReport;
use App\Exports\BulkExportmonthlyreport;
use App\Exports\BulkExportStudentList;
use Illuminate\Http\Request;
use App\Exports\BulkExport;
use App\Imports\BulkImport;
use Maatwebsite\Excel\Facades\Excel;
class ImportExportController extends Controller
{
    /**
     *
     */
    public function importExportView()
    {
        return view('importexport');
    }
    public function export()
    {
        return Excel::download(new BulkExport, 'bulkData.xlsx');
    }
    public function exportMonthly()
    {
        return Excel::download(new BulkExportmonthlyreport, 'bulkData.xlsx');
    }
    public function exportMonthlyDue()
    {
        return Excel::download(new BulkExportMonthlyDueReport, 'bulkData.xlsx');
    }
    public function exportStudentList()
    {
        return Excel::download(new BulkExportStudentList, 'bulkData.xlsx');
    }
}
