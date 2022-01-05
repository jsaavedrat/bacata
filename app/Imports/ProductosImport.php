<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductosImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection){

    	return $collection;

    }
}
