<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class Textarea extends AbstractField
{
    protected string $component = 'textarea';
    public function getType(): string { return 'textarea'; }
}
