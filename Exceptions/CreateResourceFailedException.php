<?php

declare(strict_types=1);

namespace Ship\Exceptions;

use Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateResourceFailedException extends Exception
{
    protected $code = Response::HTTP_EXPECTATION_FAILED;
    protected $message = 'Failed to create Resource.';
}
