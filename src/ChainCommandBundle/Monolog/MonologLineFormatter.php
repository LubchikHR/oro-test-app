<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Monolog;

use Monolog\Formatter\LineFormatter;

class MonologLineFormatter extends LineFormatter
{
    public function format(array $record): string
    {
        return sprintf(
            '[%s] %s' . PHP_EOL,
            $record['datetime']->format('Y-m-d H:i:s'),
            $record['message']
        );
    }
}
