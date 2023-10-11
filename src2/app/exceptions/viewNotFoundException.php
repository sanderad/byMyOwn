<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ViewNotFoundException extends Exception
{
    protected $message = 'View Path Not Found';
}