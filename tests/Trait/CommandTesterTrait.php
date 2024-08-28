<?php

declare(strict_types=1);

namespace App\Tests\Trait;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

trait CommandTesterTrait
{
    private function createCommandTester(string $commandName): CommandTester
    {
        $application = new Application(self::$kernel);

        return new CommandTester($application->find($commandName));
    }
}
