<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Pen;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithPen;

class Line extends ReportElement
{
    use WithPen;
    
    public string $direction = 'TopDown';

    public function __construct($element = null)
    {
        $this->pen = new Pen($element?->graphicElement?->pen);

        $this->direction = (string) $element['direction'] ?: $this->direction;

        parent::__construct($element);
    }

    public function direction(string $direction): static
    {
        $this->direction = $direction;
        return $this;
    }

}
