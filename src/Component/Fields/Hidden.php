<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class Hidden extends AbstractField
{
    protected string $component = 'hidden';
    public function getType(): string { return 'hidden'; }
}
