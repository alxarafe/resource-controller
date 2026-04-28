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

use Alxarafe\ResourceController\Result\PaginatedResult;

/**
 * QueryContract — Fluent query builder abstraction.
 *
 * Provides a chainable interface for building filtered, sorted,
 * paginated queries without coupling to any specific ORM.
 */
interface QueryContract
{
    public function where(string $field, string $operator, mixed $value): static;

    public function whereNull(string $field): static;

    public function whereNotNull(string $field): static;

    public function whereIn(string $field, array $values): static;

    public function whereNotIn(string $field, array $values): static;

    /**
     * Full-text search across multiple fields.
     *
     * @param string[] $fields Fields to search in.
     * @param string   $term   Search term.
     */
    public function search(array $fields, string $term): static;

    /**
     * Eager-load relations (if supported by the adapter).
     *
     * @param string[] $relations
     */
    public function with(array $relations): static;

    public function orderBy(string $field, string $direction = 'ASC'): static;

    /**
     * Execute the query and return paginated results.
     */
    public function paginate(int $limit, int $offset = 0): PaginatedResult;

    /**
     * Count total results matching current filters (ignoring limit/offset).
     */
    public function count(): int;

    /**
     * Apply a grouped condition (equivalent to WHERE (...) with nested clauses).
     *
     * @param callable(QueryContract): void $callback
     */
    public function whereGroup(callable $callback): static;

    /**
     * Apply a raw SQL condition.
     *
     * @param string $sql
     * @param array $params
     */
    public function whereRaw(string $sql, array $params = []): static;
}
