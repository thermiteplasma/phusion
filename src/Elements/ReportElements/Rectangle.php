<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Pen;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithPen;

class Rectangle extends ReportElement {

    use WithPen;

    public int $radius = 0;

    public function __construct($element = null)
    {
        parent::__construct($element);

        $this->pen = new Pen($element?->graphicElement?->pen);
        $this->radius = (int) $element['radius'] ?? $this->radius;
    }

    public function radius(int $radius): static
    {
        $this->radius = $radius;
        return $this;
    }
}

?>