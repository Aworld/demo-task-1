<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;

class EnvException extends Exception
{
    /** @var string */
    protected $message = 'The .env file is missing. More instructions under readme.';
}
