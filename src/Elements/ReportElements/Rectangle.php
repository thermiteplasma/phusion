<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

class Rectangle extends GraphicElement {

    public int $radius = 0;

    public function __construct($element)
    {
        parent::__construct($element);

        $this->radius = (int) $element['radius'] ?? $this->radius;
    }
}

?>