<?php

namespace Thermiteplasma\Phusion\Elements;

use Thermiteplasma\Phusion\Elements\Box;

class Style
{
    public ?Box $box = null;

    public string $name = '';
    public string $mode = '';
    
    public RGBColor $foreColor;
    public RGBColor $backColor;

    public function __construct($element) {
        
        $this->box = new Box($element->box);

        $this->name = (string) $element['name'] ?: $this->name;
        $this->mode = (string) $element['mode'] ?: $this->mode;

        $this->foreColor = new RGBColor($element['forecolor'] ?? '#000000');
        $this->backColor = new RGBColor($element['backcolor'] ?? '#FFFFFF');
    }

    public function shouldFill(): bool
    {
        return $this->mode === 'Opaque';
    }
}
