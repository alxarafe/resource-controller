<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class StaticText extends AbstractField
{
    protected string $component = 'static_text';
    public function getType(): string { return 'static_text'; }
}
