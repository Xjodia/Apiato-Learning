<?php

/**
 * @apiGroup           Order
 * @apiName            DeleteOrder
 *
 * @api                {DELETE} /v1/orders/:id Delete Order
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

Route::delete('orders/{id}', [Controller::class, 'deleteOrder'])
    ->middleware(['auth:api']);

