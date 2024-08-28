<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Factory\TransactionFactory;
use App\Model\Transaction;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public static function negativeCreateDataProvider(): array
    {
        return [
            [[]],
            [['amount' => 1.23]],
            [['amount' => 1.23, 'currency' => 'USD']],
            [['amount' => 1.23, 'bin' => 'bin_1']],
            [['bin' => 'bin_1', 'currency' => 'USD']],
            [['amount' => 1.23, 'bin' => '', 'currency' => '']],
        ];
    }

    public static function positiveCreateDataProvider(): array
    {
        return [
            [new Transaction(1.23, 'bin_1', 'USD'), ['amount' => 1.23, 'bin' => 'bin_1', 'currency' => 'USD']],
            [new Transaction(1.23, 'bin_1', 'EUR'), ['amount' => 1.23, 'bin' => 'bin_1', 'currency' => 'eur']],
        ];
    }

    #[DataProvider('negativeCreateDataProvider')]
    public function testNegativeCreateFromArray($data): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new TransactionFactory();
        $factory->createFromArray($data);
    }

    #[DataProvider('positiveCreateDataProvider')]
    public function testPositiveCreateFromArray($expected, $data): void
    {
        $factory = new TransactionFactory();
        $this->assertEquals($expected, $factory->createFromArray($data));
    }
}
