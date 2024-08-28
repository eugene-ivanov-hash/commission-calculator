<?php

declare(strict_types=1);

namespace App\Utils;

use function ceil;
use function number_format;

final class Precession
{
    public function ceil(int|float $value, int $precision = 2): float
    {
        $value *= (10 ** $precision);

        $value = ceil($value);

        return $value / (10 ** $precision);
    }

    public function format(float $ceil, int $precision = 2): string
    {
        return number_format($ceil, $precision, '.', '');
    }
}
