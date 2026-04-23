<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class Icon extends AbstractField
{
    protected string $component = 'icon';
    public function getType(): string
    {
 return 'icon'; 
}
}
