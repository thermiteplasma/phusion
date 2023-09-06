<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Closure;
use Thermiteplasma\Phusion\Elements\Box;
use Thermiteplasma\Phusion\Enums\TextAdjust;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithBox;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithTextElement;

class TextField extends ReportElement
{
    use WithBox;
    use WithTextElement;
    
    public string | Closure $textFieldExpression;

    public bool $isBlankWhenNull = true;
    
    public bool $isStretchWithOverflow = false;

    public TextAdjust $textAdjust = TextAdjust::CUT_TEXT;

    public function __construct($element = null)
    {
        $this->box = new Box($element?->box);
        $this->setupTextElement($element?->textElement);
        
        $this->textFieldExpression = (string) $element?->textFieldExpression;
        $this->isBlankWhenNull = (bool) $element?->isBlankWhenNull ?: $this->isBlankWhenNull;
        $this->isStretchWithOverflow = (bool) $element?->isStretchWithOverflow ?: $this->isStretchWithOverflow;
        
        if (isset($element["textAdjust"])) {
            $this->textAdjust = TextAdjust::tryFrom((string) $element["textAdjust"]);
        }
        
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

    public function textFieldExpression(string | Closure $textFieldExpression): static
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
