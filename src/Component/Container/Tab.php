<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Container;

/**
 * Tab — A single tab within a TabGroup.
 */
class Tab extends AbstractContainer
{
    private ?int $badgeCount = null;

    public function __construct(string $id, string $label, string $icon = '', array $fields = [])
    {
        parent::__construct('tab_' . $id, $label, $fields, ['icon' => $icon]);
    }

    public function getContainerType(): string
    {
        return 'tab';
    }

    public function getTabId(): string
    {
        return $this->field;
    }

    public function setBadgeCount(?int $count): void
    {
        $this->badgeCount = $count;
    }

    public function getBadgeCount(): ?int
    {
        return $this->badgeCount;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'badge' => $this->badgeCount,
        ]);
    }
}
