<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Result;

/**
 * PaginatedResult — Typed value object for paginated query results.
 *
 * All items are plain associative arrays, not ORM model instances.
 */
final class PaginatedResult
{
    /**
     * @param array<int, array<string, mixed>> $items  Result rows as associative arrays.
     * @param int                              $total  Total matching records (before pagination).
     * @param int                              $limit  Page size.
     * @param int                              $offset Current offset.
     */
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $limit,
        public readonly int $offset,
    ) {
    }
}
