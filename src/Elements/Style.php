<?php

namespace Thermiteplasma\Phusion\Elements;

use Thermiteplasma\Phusion\Elements\Box;

class Style
{
    public Box $box;

    public string $name = '';
    public string $mode = '';
    
    public RGBColor $foreColor;
    public RGBColor $backColor;

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }
    
    public function __construct($element = null) {
        
        $this->box = new Box($element?->box);

        if (isset($element['name'])) {
            $this->name = (string) $element['name'];
        }

        if (isset($element['name'])) {
            $this->mode = (string) $element['mode'];
        }
        
        $this->foreColor = new RGBColor($element['forecolor'] ?? '#000000');
        $this->backColor = new RGBColor($element['backcolor'] ?? '#FFFFFF');
    }

    public function shouldFill(): bool
    {
        return $this->mode === 'Opaque';
    }

    public function box(Box $box): static
    {
        $this->box = $box;
        return $this;
    }

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function mode(string $mode): static
    {
        $this->mode = $mode;
        return $this;
    }

    public function foreColor(RGBColor $foreColor): static
    {
        $this->foreColor = $foreColor;
        return $this;
    }

    public function backColor(RGBColor $backColor): static
    {
        $this->backColor = $backColor;
        return $this;
    }
}
