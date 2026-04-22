<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Contracts;

/**
 * RelationContract — Manages parent-child relation synchronization.
 *
 * Optional contract. Only needed if the application uses inline
 * relation editing (e.g., invoice lines within an invoice form).
 */
interface RelationContract
{
    /**
     * Synchronize child records for a parent entity.
     *
     * - Creates new rows (those without an existing ID).
     * - Updates existing rows (matched by primary key).
     * - Deletes rows that are no longer present in $rows.
     *
     * @param string|int $parentId       The parent record's ID.
     * @param string     $relationName   The relation identifier.
     * @param array      $rows           Submitted child rows.
     * @param array      $meta           Relation metadata: foreignKey, relatedKey, etc.
     */
    public function syncRelation(
        string|int $parentId,
        string $relationName,
        array $rows,
        array $meta
    ): void;
}
