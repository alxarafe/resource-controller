<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class Decimal extends AbstractField
{
    protected string $component = 'decimal';
    public function getType(): string { return 'decimal'; }
}
