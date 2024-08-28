<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class CountryNotFoundException extends Exception
{
    public function __construct(string $bin, string $data)
    {
        parent::__construct("Country for bin $bin not found. $data");
    }
}
