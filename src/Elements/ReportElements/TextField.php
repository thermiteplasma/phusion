<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Box;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithBox;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithTextElement;
use Thermiteplasma\Phusion\Enums\TextAdjust;

class TextField extends ReportElement
{
    use WithBox;
    use WithTextElement;
    
    public string $textFieldExpression;

    public bool $isBlankWhenNull = true;
    
    public bool $isStretchWithOverflow = false;

    public TextAdjust $textAdjust = TextAdjust::CUT_TEXT;

    public function __construct($element)
    {
        $this->box = new Box($element->box);
        $this->setupTextElement($element->textElement);
        
        $this->textFieldExpression = (string) $element->textFieldExpression;
        $this->isBlankWhenNull = (bool) $element->isBlankWhenNull ?: $this->isBlankWhenNull;
        $this->isStretchWithOverflow = (bool) $element->isStretchWithOverflow ?: $this->isStretchWithOverflow;
        
        $textAdjust = (string) $element["textAdjust"] ?: 'CutText';
        $this->textAdjust = TextAdjust::tryFrom($textAdjust);

        if ($this->textAdjust == TextAdjust::CUT_TEXT) {
            $this->isStretchWithOverflow = false;
        }
        
        parent::__construct($element);
    }

    public function box(Box $box): static
    {
        $this->box = $box;
        return $this;
    }

    public function textFieldExpression(string $textFieldExpression): static
    {
        $this->textFieldExpression = $textFieldExpression;
        return $this;
    }

    public function isBlankWhenNull(bool $isBlankWhenNull): static
    {
        $this->isBlankWhenNull = $isBlankWhenNull;
        return $this;
    }

    public function isStretchWithOverflow(bool $isStretchWithOverflow): static
    {
        $this->isStretchWithOverflow = $isStretchWithOverflow;
        return $this;
    }

    public function textAdjust(string $textAdjust): static
    {
        $this->textAdjust = $textAdjust;
        return $this;
    }
}
