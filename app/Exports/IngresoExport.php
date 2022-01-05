<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class IngresoExport implements FromQuery{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;


    public function query(){

    	

    	$ingresos = DB::table('ingresos_sucursal')
        ->get();

        return $ingresos;
        //
    }
}
