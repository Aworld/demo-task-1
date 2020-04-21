<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

/**
 * Class ApiException
 * Exception to handle API requests
 *
 * @package App\Exception
 */
class ApiException extends Exception
{
    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
