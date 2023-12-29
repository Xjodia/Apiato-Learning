<?php

namespace App\Containers\AppSection\Order\Exceptions;

use App\Ship\Parents\Exceptions\Exception as ParentException;
use Symfony\Component\HttpFoundation\Response;

class OrderFailedException extends ParentException
{
    protected $code = Response::HTTP_BAD_REQUEST;
    protected $message = 'Exception Message.';
}
