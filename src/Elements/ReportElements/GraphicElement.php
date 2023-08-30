<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Pen;

class GraphicElement extends ReportElement
{
    public ?Pen $pen = null;
    
    public function __construct($element)
    {
        parent::__construct($element);
        
        $graphicElement = $element->graphicElement ?? null;

        $this->pen = new Pen($graphicElement->pen ?? null);
    }
}
