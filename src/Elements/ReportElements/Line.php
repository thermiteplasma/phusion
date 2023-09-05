<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Pen;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithPen;
use Thermiteplasma\Phusion\Enums\LineDirection;

class Line extends ReportElement
{
    use WithPen;
    
    public LineDirection $direction = LineDirection::TOP_DOWN;

    public function __construct($element = null)
    {
        $this->pen = new Pen($element?->graphicElement?->pen);

        if (isset($element['direction'])) {
            $this->direction = LineDirection::tryFrom((string) $element['direction']);
        }

        parent::__construct($element);
    }

    public function direction(LineDirection $direction): static
    {
        $this->direction = $direction;
        return $this;
    }

}
