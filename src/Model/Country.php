<?php

declare(strict_types=1);

namespace App\Model;

use App\Constant\Country as CountryConstant;
use function in_array;
use function strtoupper;

final readonly class Country
{
    private string $code;

    public function __construct(
        string $code,
    ) {
        $this->code = strtoupper($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isEu(): bool
    {
        return in_array($this->code, CountryConstant::EU_COUNTRIES, true);
    }
}
