<?php

declare(strict_types=1);

namespace App\Interface;

use App\Model\Country;

interface CountryProviderInterface
{
    public function getCountry(string $bin): Country;
}
