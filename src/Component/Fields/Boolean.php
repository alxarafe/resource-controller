<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class Boolean extends AbstractField
{
    protected string $component = 'boolean';

    public function getType(): string
    {
        return 'boolean';
    }
}
