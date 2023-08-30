<?php

namespace Thermiteplasma\Phusion\Dataset;

use Closure;
use Thermiteplasma\Phusion\Enums\ResetType;
use Thermiteplasma\Phusion\Enums\VariableCalculation;

class Variable
{
    
    public function __construct(
        public Closure $variableExpression, 
        public $initialValue = null,
        public VariableCalculation $calculation = VariableCalculation::NOTHING, 
        public $resetType = ResetType::REPORT,
        public $resetGroup = '',
        public $incrementType = ResetType::NONE, 
        public $incrementGroup = '',
    ) {}
}