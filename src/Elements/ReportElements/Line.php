<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

class Line extends GraphicElement
{
    public string $direction = 'TopDown';

    public function __construct($element)
    {
        $this->direction = (string) $element['direction'] ?: $this->direction;

        parent::__construct($element);
    }

    public function direction(string $direction): static
    {
        $this->direction = $direction;
        return $this;
    }

}
