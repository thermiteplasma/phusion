<?php

namespace Thermiteplasma\Phusion\Enums;

enum PageOrientation: string
{
    case PORTRAIT = 'Portrait';
    case LANDSCAPE = 'Landscape';

    public function tcpdfValue()
    {
        return match ($this) {
            self::PORTRAIT => 'P',
            self::LANDSCAPE => 'L',
        };
    }
}
