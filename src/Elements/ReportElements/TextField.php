<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Box;
use Thermiteplasma\Phusion\Elements\ReportElements\TextElement;


class TextField extends ReportElement
{
    public ?Box $box = null;
    public TextElement $textElement;

    public string $textFieldExpression;
    public bool $isBlankWhenNull = true;
    public bool $isStretchWithOverflow = false;

    public string $textAdjust = 'CutText'; //CutText, StretchHeight, ScaleFont

    public function __construct($element)
    {
        $this->box = new Box($element->box);
        $this->textElement = new TextElement($element->textElement);
        
        $this->textFieldExpression = (string) $element->textFieldExpression;
        $this->isBlankWhenNull = (bool) $element->isBlankWhenNull ?: $this->isBlankWhenNull;
        $this->isStretchWithOverflow = (bool) $element->isStretchWithOverflow ?: $this->isStretchWithOverflow;
        $this->textAdjust = (string) $element->textAdjust ?: $this->textAdjust;

        if ($this->textAdjust == 'CutText') {
            $this->isStretchWithOverflow = false;
        }
        
        parent::__construct($element);
    }
    
}
