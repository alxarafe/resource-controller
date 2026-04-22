<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Container;

class Row extends AbstractContainer
{
    public function __construct(array $fields = [], array $options = [])
    {
        parent::__construct('row', '', $fields, $options);
    }
    public function getContainerType(): string { return 'row'; }

    public function getColClass(): string
    {
        return $this->options['col'] ?? 'col-12';
    }
}
