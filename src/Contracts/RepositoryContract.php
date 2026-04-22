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
 * RepositoryContract — Core data access abstraction.
 *
 * Implementations can wrap Eloquent, Doctrine, PDO, REST APIs, etc.
 * The ResourceController only interacts with data through this contract.
 */
interface RepositoryContract
{
    /**
     * Create a new query builder for this repository.
     */
    public function query(): QueryContract;

    /**
     * Find a single record by its primary key.
     *
     * @return array<string, mixed>|null Associative array of field => value, or null if not found.
     */
    public function find(string|int $id): ?array;

    /**
     * Return an empty record structure (for "new" forms).
     *
     * @return array<string, mixed>
     */
    public function newRecord(): array;

    /**
     * Save (create or update) a record.
     *
     * @param string|int|null $id   Null or 'new' for creation, existing ID for update.
     * @param array<string, mixed> $data Field => value pairs.
     * @return array<string, mixed> The saved record as an associative array.
     * @throws \RuntimeException If save fails.
     */
    public function save(string|int|null $id, array $data): array;

    /**
     * Delete a record by its primary key.
     */
    public function delete(string|int $id): bool;

    /**
     * Get the primary key field name.
     */
    public function getPrimaryKey(): string;

    /**
     * Get field metadata for auto-scaffolding UI components.
     *
     * Each entry should contain at minimum:
     * - 'field'       => string (column/field name)
     * - 'label'       => string (human-readable label)
     * - 'genericType' => string (text|integer|decimal|boolean|date|datetime|time|textarea)
     * - 'required'    => bool
     *
     * Optional keys: 'dbType', 'length', 'nullable', 'default', 'unsigned'
     *
     * @return array<string, array<string, mixed>>
     */
    public function getFieldMetadata(): array;

    /**
     * Check if the underlying storage (table, collection, endpoint) exists.
     */
    public function storageExists(): bool;
}
