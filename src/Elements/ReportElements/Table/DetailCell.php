<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements\Table;

use Thermiteplasma\Phusion\Elements\ElementConcerns\ContainsChildElements;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithComponents;

class DetailCell
{
    use WithComponents;

    public int $height = 30;
    
    public string $style = '';

    public function __construct($element = null)
    {   
        if (isset($element->attributes()['height'])) {
            $this->height = (int) $element->attributes()['height'];
        }

        if (isset($element->attributes()['style'])) {
            $this->style = (string) $element->attributes()['style'];
        }
        
        if ($element) {
            $this->processChildren($element);
        }
    }
}
