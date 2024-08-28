<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Utils\Precession;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PrecessionTest extends TestCase
{
    public static function ceilDataProvider(): array
    {
        return [
            [1.23, 1.225],
            [1.23, 1.23],
            [1.24, 1.234],
            [1.24, 1.2301],
            [1.24, 1.24],
        ];
    }

    #[DataProvider('ceilDataProvider')]
    public function testCeil($expected, $value, $precision = 2): void
    {
        $precession = new Precession();
        $this->assertEquals($expected, $precession->ceil($value, $precision));
    }
}
