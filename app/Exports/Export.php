<?php

namespace App\Exports;

use App\Models\Serverserviceorder;
use Maatwebsite\Excel\Concerns\FromCollection;

class Export implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Serverserviceorder::all();
    }
}
