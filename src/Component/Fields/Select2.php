<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class Select2 extends AbstractField
{
    protected string $component = 'select2';
    public function getType(): string { return 'select2'; }
}
