<?php

namespace Thermiteplasma\Phusion\Elements;

class RGBColor
{
    public int $red = 0;
    public int $green = 0;
    public int $blue = 0;

    public function __construct(string $hexColor) {
        
        $this->red = hexdec(substr($hexColor, 1, 2));
        $this->green = hexdec(substr($hexColor, 3, 2));
        $this->blue = hexdec(substr($hexColor, 5, 2));
    }

    public function toArray()
    {
        return [
            'r' => $this->red,
            'g' => $this->green,
            'b' => $this->blue,
        ];
    }
}
