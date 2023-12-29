<?php

namespace App\Containers\AppSection\Product\Actions;

use App\Containers\AppSection\Product\Tasks\ImportProductsTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportProductsAction extends ParentAction
{
    public function run(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');

            app(ImportProductsTask::class)->run($file);

            return response()->json(['success' => 'Dữ liệu đã được nhập vào cơ sở dữ liệu thành công.']);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 500);
        }
    }
}
