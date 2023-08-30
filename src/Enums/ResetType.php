<?php

namespace Thermiteplasma\Phusion\Enums;

enum ResetType: string
{
    case NONE = 'None';
    case REPORT = 'Report';
    case PAGE = 'Page';
    case COLUMN = 'Column';
    case GROUP = 'Group';
}
