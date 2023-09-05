<?php

namespace Thermiteplasma\Phusion\Enums;

enum StretchType: string
{
    case NO_STRETCH = 'NoStretch';
    case RELATIVE_TO_TALLEST_OBJECT = 'RelativeToTallestObject';
    case RELATIVE_TO_BAND_HEIGHT = 'RelativeToBandHeight';
}
