<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Container;

use JsonSerializable;

/**
 * AbstractContainer — Base class for layout containers (Panel, Tab, Row, etc.).
 *
 * Containers hold child fields or other containers, forming a tree structure
 * that the renderer traverses to build the UI.
 */
abstract class AbstractContainer implements JsonSerializable
{
    protected string $field;
    protected string $label;

    /** @var array<int, mixed> Child fields or containers */
    protected array $fields;

    protected array $options;

    public function __construct(string $field, string $label = '', array $fields = [], array $options = [])
    {
        $this->field = $field;
        $this->label = $label;
        $this->fields = $fields;
        $this->options = $options;
    }

    abstract public function getContainerType(): string;

    public function getField(): string
    {
        return $this->field;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getColClass(): string
    {
        return $this->options['col'] ?? 'col-12';
    }

    /**
     * Filter children using a callback.
     *
     * @param callable(mixed): bool $callback
     */
    public function filterChildren(callable $callback): void
    {
        $this->fields = array_values(array_filter($this->fields, $callback));
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'container' => $this->getContainerType(),
            'field' => $this->field,
            'label' => $this->label,
            'fields' => $this->fields,
            'options' => $this->options,
        ];
    }
}
