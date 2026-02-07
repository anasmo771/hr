<?php

namespace App\Exports;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;

class employeesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $emp = Employee::all();
        return $emp;

        // return Employee::all();
    }
}
