<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct("File not found: $filePath");
    }
}
