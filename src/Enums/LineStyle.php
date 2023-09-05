<?php

namespace Thermiteplasma\Phusion\Enums;

enum LineStyle: string
{
    case SOLID = 'Solid';
    case DASHED = 'Dashed';
    case DOTTED = 'Dotted';
    
    public function tcpdfValue()
    {
        return match ($this) {
            self::DOTTED => '0,1',
            self::DASHED => '4,2',
            self::SOLID => '0',
            default => '0',
        };
    }
}