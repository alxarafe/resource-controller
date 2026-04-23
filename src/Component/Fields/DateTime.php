<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class DateTime extends AbstractField
{
    protected string $component = 'datetime';
    public function getType(): string
    {
 return 'datetime'; 
}
}
