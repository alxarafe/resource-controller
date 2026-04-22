<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class Text extends AbstractField
{
    protected string $component = 'text';
    public function getType(): string { return 'text'; }
}
