<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class Date extends AbstractField
{
    protected string $component = 'date';
    public function getType(): string
    {
 return 'date'; 
}
}
