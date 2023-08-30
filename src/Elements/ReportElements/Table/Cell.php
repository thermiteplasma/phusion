<?php
namespace Thermiteplasma\Phusion\Elements\ReportElements\Table;

use Thermiteplasma\Phusion\Elements\ElementConcerns\ContainsChildElements;

class Cell
{
    use ContainsChildElements;
    
    public int $rowSpan = 1;

    public int $height = 30;
    
    public string $style = '';

    public function __construct($element)
    {
        $this->rowSpan = (int) $element->attributes()['rowSpan'] ?: $this->rowSpan;
        $this->height = (int) $element->attributes()['height'] ?: $this->height;
        $this->style = (string) $element->attributes()['style'] ?: $this->style;

        $this->processChildren($element);
    }
}
