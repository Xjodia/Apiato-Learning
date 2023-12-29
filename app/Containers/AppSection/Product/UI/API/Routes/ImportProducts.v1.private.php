<?php

/**
 * @apiGroup           Product
 * @apiName            ImportProducts
 *
 * @api                {POST} /v1/import-products Import Products
 * @apiDescription     Endpoint description here...
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} parameters here...
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     // Insert the response of the request here...
 * }
 */

use App\Containers\AppSection\Product\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post('import-products', [Controller::class, 'importProducts'])
    ->middleware(['auth:api']);

