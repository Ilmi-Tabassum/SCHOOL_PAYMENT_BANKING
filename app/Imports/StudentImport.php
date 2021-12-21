<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Student([
                        'name'         => $row['name'], 
                        'student_id'       => $row['student_id'], 
                        // 'Father Name'          => $row[2], 
                        // 'Mother Name'          => $row[3], 
                        // 'Date of Birth'        => $row[4], 
                        // 'Mobile No'            => $row[5], 
                        // 'Email Address'        => $row[6], 
                        // 'Present Address'      => $row[7], 
                        // 'Permanent Address'    => $row[8], 
                        // 'Blood Group'          => $row[9]
                    ]);
    }
}
