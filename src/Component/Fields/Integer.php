<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class Integer extends AbstractField
{
    protected string $component = 'integer';
    public function getType(): string
    {
 return 'integer'; 
}
}
