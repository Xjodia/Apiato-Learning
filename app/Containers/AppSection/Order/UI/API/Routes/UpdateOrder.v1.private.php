<?php

/**
 * @apiGroup           Order
 * @apiName            UpdateOrder
 *
 * @api                {PATCH} /v1/orders/:id Update Order
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

use App\Containers\AppSection\Order\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::patch('orders/{id}', [Controller::class, 'updateOrder'])
    ->middleware(['auth:api']);

