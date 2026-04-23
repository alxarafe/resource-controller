<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class StaticText extends AbstractField
{
    protected string $component = 'static_text';
    
    public function __construct(string $text, array $options = [])
    {
        parent::__construct(uniqid('static_'), $text, $options);
    }
    
    public function getType(): string
    {
 return 'static_text'; 
}
}
