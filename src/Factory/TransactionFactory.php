<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\Transaction;
use InvalidArgumentException;
use function array_map;
use function is_numeric;

class TransactionFactory
{
    public function createFromArray(array $data): Transaction
    {
        $data = array_map('\trim', $data);

        if (!isset($data['amount']) || !is_numeric($data['amount'])) {
            throw new InvalidArgumentException('Data does contain empty or invalid amount');
        }

        if (empty($data['currency'])) {
            throw new InvalidArgumentException('Data does not contain currency');
        }

        if (empty($data['bin'])) {
            throw new InvalidArgumentException('Data does not contain bin');
        }

        return new Transaction(
            (float) $data['amount'],
            $data['bin'],
            $data['currency'],
        );
    }
}
