<?php

namespace Thermiteplasma\Phusion\Enums;

enum ScaleImage: string
{
    case CLIP = 'Clip';
    case FILL_FRAME = 'FillFrame';
    case RETAIN_SHAPE = 'RetainShape';
    case REAL_HEIGHT = 'RealHeight';
    case REAL_SIZE = 'RealSize';
}