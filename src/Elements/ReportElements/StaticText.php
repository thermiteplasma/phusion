<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Closure;
use Thermiteplasma\Phusion\Elements\Box;
use Thermiteplasma\Phusion\Elements\ReportElements\TextElement;

class StaticText extends ReportElement
{
    public ?Box $box = null;
    public TextElement $textElement;
    
    public string $text;

    public function __construct($element)
    {
        $this->box = new Box($element->box);
        $this->textElement = new TextElement($element->textElement);
        
        $this->text = (string) $element->text;
        
        parent::__construct($element);
    }

    public function text($text)
    {
        $this->text = $text;
        return $this;
    }

    public function box(Box | Closure $box)
    {
        $this->box = $box;
        return $this;
    }

    public function textElement(TextElement $textElement)
    {
        $this->textElement = $textElement;
        return $this;
    }
    
}
