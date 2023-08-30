<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

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
    
}
