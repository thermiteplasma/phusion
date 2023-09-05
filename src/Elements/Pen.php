<?php

namespace Thermiteplasma\Phusion\Elements;

use Thermiteplasma\Phusion\Elements\RGBColor;
use Thermiteplasma\Phusion\Enums\LineStyle;

class Pen
{
    public float $lineWidth = 0;
    public LineStyle $lineStyle = LineStyle::SOLID;
    
    public RGBColor $lineColor;

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }

    public function __construct($pen = null)
    {
        if (isset($pen['lineWidth'])) {
            $this->lineWidth = (float) $pen['lineWidth'];
        }

        if (isset($pen['lineStyle'])) {
            $this->lineStyle = LineStyle::tryFrom((string) $pen['lineStyle']);
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
            'dash' => $this->lineStyle->tcpdfValue(),
        ];
    }

    public function lineWidth($lineWidth)
    {
        $this->lineWidth = $lineWidth;
        return $this;
    }

    public function lineStyle(LineStyle $lineStyle)
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
