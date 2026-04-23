<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Container;

class Separator extends AbstractContainer
{
    public function __construct(string $label = '')
    {
        parent::__construct('separator', $label, []);
    }
    public function getContainerType(): string
    {
 return 'separator'; 
}

    public function getColClass(): string
    {
        return $this->options['col'] ?? 'col-12';
    }
}
