<?php

namespace Thermiteplasma\Phusion\Elements;

use Thermiteplasma\Phusion\Elements\RGBColor;

class Pen
{
    public float $lineWidth = 0;
    public string $lineStyle = 'Solid';
    
    public RGBColor $lineColor;

    public function __construct($pen = null)
    {
        if (isset($pen['lineWidth'])) {
            $this->lineWidth = (float) $pen['lineWidth'];
        }

        if (isset($pen['lineStyle'])) {
            $this->lineStyle = (string) $pen['lineStyle'];
        }

        $this->lineColor = new RGBColor($pen['lineColor'] ?? '#000000');
    }

    public function border()
    {
        return [
            'width' => $this->lineWidth,
            'color' => $this->lineColor->toArray(),
            'cap' => 'square',
            'join' => 'miter',
            'dash' => $this->dash(),
        ];
    }

    public function dash()
    {
        return match ($this->lineStyle) {
            'Dotted' => '0,1',
            'Dashed' => '4,2',
            'Solid' => '0',
            default => '0',
        };
    }

    public function lineWidth($lineWidth)
    {
        $this->lineWidth = $lineWidth;
        return $this;
    }

    public function lineStyle($lineStyle)
    {
        $this->lineStyle = $lineStyle;
        return $this;
    }

    public function lineColor(RGBColor $lineColor)
    {
        $this->lineColor = $lineColor;
        return $this;
    }
}
