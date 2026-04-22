<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class Image extends AbstractField
{
    protected string $component = 'image';
    public function getType(): string { return 'image'; }
}
