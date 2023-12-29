<?php

namespace App\Containers\AppSection\Product\Tasks;

use App\Containers\AppSection\Product\Import\ErrorsExport;
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

        $import = new ProductsImport();
        $import->setFilePath($filePath);

        try {
            Excel::import($import, storage_path("app/public/$filePath"));
        } catch (\Exception $e) {
            $time = time();
            // Xử lý ngoại lệ và xuất thông tin lỗi vào file Excel
            $errorLogExport = new ErrorsExport($import->getErrors());
            Excel::store($errorLogExport, "public/error-logs/error_excel-$time.xlsx");

            throw new \Exception('Import failed. Check the error log for details.');
        }

        // Export chỉ cột 'error' vào file Excel
        $errors = $import->getErrors();
        if (!empty($errors)) {
            $time = time(); // Lấy thời gian hiện tại
            $errorLogExport = new ErrorsExport($errors);
            Excel::store($errorLogExport, "public/error-logs/error_excel-$time.xlsx");
            throw new \Exception('Import completed with errors. Check the error log for details.');
        }
    }
}
