<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class Select2 extends AbstractField
{
    protected string $component = 'select2';
    
    public function __construct(string $field, string $label = '', array $choices = [], array $options = [])
    {
        $options['values'] = $choices;
        if (!isset($options['class'])) {
            $options['class'] = 'select2';
        } else {
            $options['class'] .= ' select2';
        }
        parent::__construct($field, $label, $options);
    }
    
    public function getType(): string
    {
 return 'select2'; 
}
}
