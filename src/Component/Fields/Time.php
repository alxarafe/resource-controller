<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class Time extends AbstractField
{
    protected string $component = 'time';
    public function getType(): string { return 'time'; }
}
