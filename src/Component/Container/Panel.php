<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Container;

/**
 * Panel — A visual grouping container with a title and grid column support.
 */
class Panel extends AbstractContainer
{
    public function __construct(string $label, array $fields = [], array $options = [])
    {
        $field = 'panel_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $label) ?? 'default');
        parent::__construct($field, $label, $fields, $options);
    }

    public function getContainerType(): string
    {
        return 'panel';
    }

    public function getColClass(): string
    {
        return $this->options['col'] ?? 'col-md-6';
    }
}
