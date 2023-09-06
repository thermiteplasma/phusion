<?php

namespace Thermiteplasma\Phusion\Dataset;

use Closure;
use Thermiteplasma\Phusion\Enums\ResetType;
use Thermiteplasma\Phusion\Enums\VariableCalculation;

class Variable
{
    public Closure | null $variableExpression;
    
    public $initialValue = null;
    
    public VariableCalculation $calculation = VariableCalculation::NOTHING;
    
    public $resetType = ResetType::REPORT;
    
    public $resetGroup = '';
    
    public $incrementType = ResetType::NONE;
    
    public $incrementGroup = '';

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }

    public function variableExpression(Closure $variableExpression): static
    {
        $this->variableExpression = $variableExpression;
        return $this;
    }

    public function initialValue($initialValue): static
    {
        $this->initialValue = $initialValue;
        return $this;
    }

    public function calculation(VariableCalculation $calculation): static
    {
        $this->calculation = $calculation;
        return $this;
    }

    public function resetType(ResetType $resetType): static
    {
        $this->resetType = $resetType;
        return $this;
    }

    public function resetGroup(string $resetGroup): static
    {
        $this->resetGroup = $resetGroup;
        return $this;
    }

    public function incrementType(ResetType $incrementType): static
    {
        $this->incrementType = $incrementType;
        return $this;
    }

    public function incrementGroup(string $incrementGroup): static
    {
        $this->incrementGroup = $incrementGroup;
        return $this;
    }
}