<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\FileNotFoundException;
use App\Factory\TransactionFactory;
use App\Service\CommissionService;
use App\Utils\Precession;
use JsonException;
use PHPUnit\Util\InvalidJsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function fgets;
use function file_exists;
use function fopen;
use function json_decode;
use function sprintf;
use function trim;
use const JSON_THROW_ON_ERROR;

#[AsCommand(
    name: 'app:calculate:commissions',
    description: 'Calculate commissions for transactions from file',
)]
class CalculateCommissionsCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly TransactionFactory $transactionFactory,
        private readonly CommissionService $commissionService,
        private readonly Precession $precessionService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filePath', InputArgument::REQUIRED, 'Path to transactions file');
    }

    /**
     * @throws FileNotFoundException
     * @throws JsonException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $filePath = $input->getArgument('filePath');

        $this->debug('File path to process: '.$filePath);

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($filePath);
        }

        $file = fopen($filePath, 'rb');

        while ($row = fgets($file)) {
            $this->debug(sprintf('Processing row: %s', trim($row)));
            if (json_validate($row) === false) {
                throw new InvalidJsonException("Invalid JSON format. $row");
            }

            $this->debug('Creating transaction from row');
            $transaction = $this->transactionFactory->createFromArray(
                json_decode($row, true, 512, JSON_THROW_ON_ERROR)
            );

            $this->debug('Calculating commission');
            $commission = $this->commissionService->calculate($transaction);

            $this->io->writeln(
                $this->precessionService->format($this->precessionService->ceil($commission->getAmount())),
            );
        }

        return Command::SUCCESS;
    }

    private function debug($message): void
    {
        if ($this->io->isDebug()) {
            $this->io->text($message);
        }
    }
}
