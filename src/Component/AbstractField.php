<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component;

use Alxarafe\ResourceController\Component\Enum\ActionPosition;
use JsonSerializable;

/**
 * AbstractField — Base class for all UI field components.
 *
 * Provides a framework-agnostic representation of a form/list field
 * that can be serialized to JSON for frontend consumption.
 */
abstract class AbstractField implements JsonSerializable
{
    protected string $component = 'text';
    protected string $field;
    protected string $label;
    protected array $options = [];

    /** @var array<int, array<string, mixed>> */
    protected array $actions = [];

    /**
     * Callback to determine field visibility.
     * If null, the field is always visible.
     *
     * @var (callable(): bool)|null
     */
    protected $visibilityCallback = null;

    public function __construct(string $field, string $label, array $options = [])
    {
        $this->field = $field;
        $this->label = $label;

        if (!empty($options) && !array_key_exists('options', $options)) {
            $this->options = ['options' => $options];
        } else {
            $this->options = $options;
        }
    }

    abstract public function getType(): string;

    public function getField(): string
    {
        return $this->field;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getComponent(): string
    {
        return $this->component;
    }

    public function getColClass(): string
    {
        return $this->options['options']['col'] ?? $this->options['col'] ?? 'col-12';
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set a visibility callback for conditional field rendering.
     *
     * @param callable(): bool $callback
     */
    public function setVisibility(callable $callback): self
    {
        $this->visibilityCallback = $callback;
        return $this;
    }

    /**
     * Check if this field should be visible.
     */
    public function isVisible(): bool
    {
        if ($this->visibilityCallback !== null) {
            return call_user_func($this->visibilityCallback);
        }
        return true;
    }

    /**
     * Add an action button to the field.
     */
    public function addAction(
        string $icon,
        string $onclick,
        string $title = '',
        string $class = 'btn-outline-secondary',
        ActionPosition $position = ActionPosition::Left
    ): self {
        $this->actions[] = [
            'icon' => $icon,
            'onclick' => $onclick,
            'title' => $title,
            'class' => $class,
            'position' => $position->value,
        ];
        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function clearActions(): self
    {
        $this->actions = [];
        return $this;
    }

    public function mergeOptions(array $newOptions): void
    {
        if (isset($this->options['options'])) {
            $this->options['options'] = array_merge($this->options['options'], $newOptions);
        } else {
            $this->options['options'] = $newOptions;
        }
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        $data = array_merge([
            'field' => $this->field,
            'name' => $this->field,
            'label' => $this->label,
            'component' => $this->component,
            'type' => $this->getType(),
            'actions' => $this->actions,
        ], $this->options);

        if (isset($data['options']) && is_array($data['options'])) {
            $data = array_merge($data, $data['options']);
        }

        return $data;
    }
}
