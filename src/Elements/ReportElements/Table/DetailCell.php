<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements\Table;

use Thermiteplasma\Phusion\Elements\ElementConcerns\ContainsChildElements;

class DetailCell
{
    use ContainsChildElements;

    public int $height = 30;
    
    public string $style = '';

    public function __construct($element)
    {
        $this->height = (int) $element->attributes()['height'] ?: $this->height;
        $this->style = (string) $element->attributes()['style'] ?: $this->style;

        $this->processChildren($element);
    }
}
