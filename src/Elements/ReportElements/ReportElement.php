<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\RGBColor;
use Thermiteplasma\Phusion\Enums\PositionType;
use Thermiteplasma\Phusion\Enums\StretchType;

class ReportElement
{
    public int $x = 0;
    public int $y = 0;
    public int $width = 0;
    public int $height = 0;

    public PositionType $positionType = PositionType::FIX_RELATIVE_TO_TOP;
    public StretchType $stretchType = StretchType::NO_STRETCH;
    public string $mode = '';

    public RGBColor $foreColor;
    public RGBColor $backColor;

    public bool $isPrintWhenDetailOverflows = false;

    public function __construct($element = null)
    {
        
        $reportElement = $element?->reportElement;

        $this->x = (int) $reportElement['x'] ?: $this->x;
        $this->y = (int) $reportElement['y'] ?: $this->y;
        $this->width = (int) $reportElement['width'] ?: $this->width;
        $this->height = (int) $reportElement['height'] ?: $this->height;

        $positionType = (string) $element["positionType"] ?: 'FixRelativeToTop';
        $this->positionType = PositionType::tryFrom($positionType);

        $stretchType = (string) $element["stretchType"] ?: 'NoStretch';
        $this->stretchType = StretchType::tryFrom($stretchType);

        $this->mode = (string) $reportElement['mode'] ?: $this->mode;
        
        $this->isPrintWhenDetailOverflows = (bool) $reportElement['isPrintWhenDetailOverflows'] ?: $this->isPrintWhenDetailOverflows;

        $this->foreColor = new RGBColor($reportElement['forecolor'] ?? '#000000');
        $this->backColor = new RGBColor($reportElement['backcolor'] ?? '#FFFFFF');
        
       
    }

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }
    
    public function shouldFill(): bool
    {
        return $this->mode === 'Opaque';
    }

    public function positionType($positionType)
    {
        $this->positionType = $positionType;
        return $this;
    }

    public function stretchType($stretchType)
    {
        $this->stretchType = $stretchType;
        return $this;
    }

    public function foreColor($foreColor)
    {
        $this->foreColor = $foreColor;
        return $this;
    }

    public function backColor($backColor)
    {
        $this->backColor = $backColor;
        return $this;
    }

    public function mode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    public function isPrintWhenDetailOverflows($isPrintWhenDetailOverflows)
    {
        $this->isPrintWhenDetailOverflows = $isPrintWhenDetailOverflows;
        return $this;
    }

    public function position($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
        return $this;
    }

    public function size($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }
}
