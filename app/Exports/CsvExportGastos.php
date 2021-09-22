<?php

namespace App\Exports;

use App\GastosVeiculos;
use Maatwebsite\Excel\Concerns\FromCollection;

class CsvExportGastos implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return GastosVeiculos::all();
    }
}
