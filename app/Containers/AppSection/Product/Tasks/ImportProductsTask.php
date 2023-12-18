<?php

namespace App\Containers\AppSection\Product\Tasks;

use App\Containers\AppSection\Product\Import\ProductsImport;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductsTask extends ParentTask
{
    public function __construct()
    {
        // ..
    }

    public function run($file)
    {
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('excel-imports', $fileName, 'public');
        Excel::import(new ProductsImport(), storage_path("app/public/$filePath"));
    }
}
