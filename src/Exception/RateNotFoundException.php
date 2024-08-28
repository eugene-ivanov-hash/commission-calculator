<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class RateNotFoundException extends Exception
{
    public function __construct(string $currency)
    {
        parent::__construct("Rate for currency $currency not found");
    }
}
