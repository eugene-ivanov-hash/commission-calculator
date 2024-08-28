<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class RateLimitException extends Exception
{
    public function __construct(string $apiUrl, string $message)
    {
        parent::__construct("Rate limit reached for API $apiUrl. $message");
    }
}
