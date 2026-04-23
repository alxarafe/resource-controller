<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Fields;

use Alxarafe\ResourceController\Component\AbstractField;

class Image extends AbstractField
{
    protected string $component = 'image';
    
    public function __construct(string $url, string $label = '', array $options = [])
    {
        $options['url'] = $url;
        $options['src'] = $url;
        parent::__construct(uniqid('img_'), $label, $options);
    }
    
    public function getType(): string
    {
 return 'image'; 
}
}
