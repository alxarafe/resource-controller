<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Null;

use Alxarafe\ResourceController\Contracts\TransactionContract;

/**
 * NullTransaction — No-op implementation for adapters without transaction support.
 */
final class NullTransaction implements TransactionContract
{
    public function begin(): void
    {
    }

    public function commit(): void
    {
    }

    public function rollback(): void
    {
    }

    public function wrap(callable $callback): mixed
    {
        return $callback();
    }
}
