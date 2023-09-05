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
        if (isset($element->reportElement['x'])) {
            $this->x = (int) $element->reportElement['x'] ?: $this->x;
        }

        if (isset($element->reportElement['y'])) {
            $this->y = (int) $element->reportElement['y'] ?: $this->y;
        }

        if (isset($element->reportElement['width'])) {
            $this->width = (int) $element->reportElement['width'] ?: $this->width;
        }

        if (isset($element->reportElement['height'])) {
            $this->height = (int) $element->reportElement['height'] ?: $this->height;
        }

        if (isset($element->reportElement['positionType'])) {
            $this->positionType = PositionType::tryFrom((string) $element["positionType"]);
        }

        if (isset($element->reportElement['stretchType'])) {
            $this->stretchType = StretchType::tryFrom((string) $element["stretchType"]);
        }

        if (isset($element->reportElement['mode'])) {
            $this->mode = (string) $element->reportElement['mode'];
        }

        if (isset($element->reportElement['isPrintWhenDetailOverflows'])) {
            $this->isPrintWhenDetailOverflows = (bool) $element->reportElement['isPrintWhenDetailOverflows'];
        }
        
        $this->foreColor = new RGBColor($element?->reportElement['forecolor'] ?? '#000000');
        $this->backColor = new RGBColor($element?->reportElement['backcolor'] ?? '#FFFFFF');
        
       
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
