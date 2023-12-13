<?php

namespace App\Containers\AppSection\Product\Actions;

use Apiato\Core\Exceptions\IncorrectIdException;
use App\Containers\AppSection\Product\Tasks\ExportProductTask;
use App\Containers\AppSection\Product\UI\API\Requests\ExportProductRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\JsonResponse;

class ExportProductAction extends ParentAction
{
    /**
     * @param ExportProductRequest $request
     * @return array
     * @throws IncorrectIdException
     */
    public function run(ExportProductRequest $request): array
    {
        $fields = [
            'email',
        ];
        $data = $request->sanitizeInput($fields);
        app(ExportProductTask::class)->run($data);
        // $var = app(Task::class)->run($arg1, $arg2);
        return $data;
    }
}
