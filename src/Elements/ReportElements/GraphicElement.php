<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Closure;
use Thermiteplasma\Phusion\Elements\Pen;

class GraphicElement extends ReportElement
{
    public ?Pen $pen = null;
    
    public function __construct($element)
    {
        parent::__construct($element);
        
        $this->pen = new Pen($element?->graphicElement?->pen);
    }

    public function pen(Pen | Closure $pen)
    {
        $this->pen = $pen;
        return $this;
    }
}
