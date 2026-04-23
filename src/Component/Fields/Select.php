<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class Select extends AbstractField
{
    protected string $component = 'select';
    
    public function __construct(string $field, string $label = '', array $choices = [], array $options = [])
    {
        $options['values'] = $choices;
        parent::__construct($field, $label, $options);
    }
    
    public function getType(): string
    {
 return 'select'; 
}
}
