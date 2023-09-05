<?php

namespace Thermiteplasma\Phusion\Enums;

enum PositionType: string
{
    case FLOAT = 'Float';
    case FIX_RELATIVE_TO_TOP = 'FixRelativeToTop';
    case FIX_RELATIVE_TO_BOTTOM = 'FixRelativeToBottom';
}
