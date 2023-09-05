<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Box;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithBox;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithTextElement;

class StaticText extends ReportElement
{
    use WithBox;
    use WithTextElement;


    public string $text;

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }

    public function __construct($element = null)
    {
        $this->box = new Box($element?->box);
        
        $this->setupTextElement($element?->textElement);
        
        $this->text = (string) $element?->text;
        
        parent::__construct($element);
    }

    public function text($text)
    {
        $this->text = $text;
        return $this;
    }
}
