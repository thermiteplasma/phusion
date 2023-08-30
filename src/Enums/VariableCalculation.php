<?php

namespace Thermiteplasma\Phusion\Enums;

enum VariableCalculation: string
{
    case NOTHING = 'Nothing';
    case COUNT = 'Count';
    case DISTINCT_COUNT = 'DistinctCount';
    case SUM = 'Sum';
    case AVERAGE = 'Average';
    case LOWEST = 'Lowest';
    case HIGHEST = 'Highest';
    case STANDARD_DEVIATION = 'StandardDeviation';
    case VARIANCE = 'Variance';
    case SYSTEM = 'System';
    case FIRST = 'First';
}
