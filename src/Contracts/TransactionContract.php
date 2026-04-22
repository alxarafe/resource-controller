<?php

declare(strict_types=1);

/*
 * Copyright (C) 2024-2026 Rafael San José <rsanjose@alxarafe.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Alxarafe\ResourceController\Contracts;

/**
 * TransactionContract — Database transaction abstraction.
 *
 * Implementations wrap the underlying storage transaction mechanism.
 * A NullTransaction is provided for adapters that don't support transactions.
 */
interface TransactionContract
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;

    /**
     * Execute a callback within a transaction.
     * Automatically commits on success, rolls back on exception.
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     * @throws \Throwable Re-throws the exception after rollback.
     */
    public function wrap(callable $callback): mixed;
}
