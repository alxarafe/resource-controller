<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Container;

/**
 * TabGroup — A group of Tab containers rendered as a tabbed interface.
 */
class TabGroup extends AbstractContainer
{
    /**
     * @param Tab[]  $tabs
     * @param array  $options
     */
    public function __construct(array $tabs, array $options = [])
    {
        parent::__construct('tab_group', '', $tabs, $options);
    }

    public function getContainerType(): string
    {
        return 'tab_group';
    }
}
