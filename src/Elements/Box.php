<?php

namespace Thermiteplasma\Phusion\Elements;

use Thermiteplasma\Phusion\Elements\Pen;

Class Box
{
    public Pen $topPen;
    public Pen $bottomPen;
    public Pen $rightPen;
    public Pen $leftPen;

    public int $topPadding = 0;
    public int $bottomPadding = 0;
    public int $rightPadding = 0;
    public int $leftPadding = 0;

    public function __construct($element = null)
    {
        $this->topPen = new Pen($element->topPen ?? null);
        $this->bottomPen = new Pen($element->bottomPen ?? null);
        $this->rightPen = new Pen($element->rightPen ?? null);
        $this->leftPen = new Pen($element->leftPen ?? null);

        if (isset($element['padding'])) {
            $this->topPadding = (int)$element['padding'];
            $this->bottomPadding = (int)$element['padding'];
            $this->rightPadding = (int)$element['padding'];
            $this->leftPadding = (int)$element['padding'];
        } else {
            $this->topPadding = (int)$element['topPadding'] ?: $this->topPadding;
            $this->bottomPadding = (int)$element['bottomPadding'] ?: $this->bottomPadding;
            $this->rightPadding = (int)$element['rightPadding'] ?: $this->rightPadding;
            $this->leftPadding = (int)$element['leftPadding'] ?: $this->leftPadding;
        }

    }

    public function getBorders()
    {
        $border = Array();
        
        if ($this->topPen->lineWidth > 0) {
            $border["T"] = $this->topPen->border();
        }

        if ($this->leftPen->lineWidth > 0) {
            $border["L"] = $this->leftPen->border();
        }

        if ($this->bottomPen->lineWidth > 0) {
            $border["B"] = $this->bottomPen->border();
        }

        if ($this->rightPen->lineWidth > 0) {
            $border["R"] = $this->rightPen->border();
        }

        return $border;
    }
}
