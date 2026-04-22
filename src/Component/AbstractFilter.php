<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component;

/**
 * AbstractFilter — Base class for list view filters.
 */
abstract class AbstractFilter
{
    protected string $field;
    protected string $label;
    protected string $type;
    protected array $options;

    public function __construct(string $field, string $label, string $type = 'text', array $options = [])
    {
        $this->field = $field;
        $this->label = $label;
        $this->type = $type;
        $this->options = $options;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Apply this filter to a query.
     *
     * @param \Alxarafe\ResourceController\Contracts\QueryContract $query
     * @param mixed $value The filter value from request.
     */
    abstract public function apply(\Alxarafe\ResourceController\Contracts\QueryContract $query, mixed $value): void;
}
