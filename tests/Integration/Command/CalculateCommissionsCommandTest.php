<?php

declare(strict_types=1);

namespace App\Tests\Integration\Command;

use App\Api\CountryProvider;
use App\Api\CurrencyRateProvider;
use App\Tests\Trait\CommandTesterTrait;
use App\Tests\Trait\MockCountryProviderTrait;
use App\Tests\Trait\MockCurrencyRateProviderTrait;
use PHPUnit\Util\InvalidJsonException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;

class CalculateCommissionsCommandTest extends KernelTestCase
{
    use MockCountryProviderTrait;
    use MockCurrencyRateProviderTrait;
    use CommandTesterTrait;

    public function testExecutePositive(): void
    {
        $commandTester = $this->createCommandTester('app:calculate:commissions');

        $result = $commandTester->execute([
            'filePath' => 'tests/Integration/Command/fixtures/transactions_positive.txt',
        ]);

        $this->assertEquals(Command::SUCCESS, $result);

        $output = $commandTester->getDisplay();
        $this->assertEquals("1.00\n0.91\n25.00\n", $output);
    }

    public function testExecuteNegative(): void
    {
        $this->expectException(InvalidJsonException::class);

        $commandTester = $this->createCommandTester('app:calculate:commissions');

        $commandTester->execute([
            'filePath' => 'tests/Integration/Command/fixtures/transactions_negative.txt',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCountryProvider();
        $this->mockCurrencyRateProvider();
    }

    private function mockCountryProvider(): void
    {
        $countryProvider = new CountryProvider($this->mockCountryProviderClient());

        self::getContainer()->set(CountryProvider::class, $countryProvider);
    }

    private function mockCurrencyRateProvider(): void
    {
        $currencyRateProvider = new CurrencyRateProvider($this->mockCurrencyRateProviderClient());

        self::getContainer()->set(CurrencyRateProvider::class, $currencyRateProvider);
    }
}
