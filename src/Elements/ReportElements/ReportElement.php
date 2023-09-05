<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\RGBColor;

class ReportElement
{
    public int $x = 0;
    public int $y = 0;
    public int $width = 0;
    public int $height = 0;

    public string $positionType = 'FixRelativeToTop';
    public string $stretchType = 'NoStretch';
    public string $mode = '';

    public RGBColor $foreColor;
    public RGBColor $backColor;

    public bool $isPrintWhenDetailOverflows = false;

    public $key = null;

    public function __construct($element = null)
    {
        if ($element) {
            $reportElement = $element->reportElement;

            $this->x = (int) $reportElement['x'] ?: $this->x;
            $this->y = (int) $reportElement['y'] ?: $this->y;
            $this->width = (int) $reportElement['width'] ?: $this->width;
            $this->height = (int) $reportElement['height'] ?: $this->height;
            $this->positionType = (string) $reportElement['positionType'] ?: $this->positionType;
            $this->stretchType = (string) $reportElement['stretchType'] ?: $this->stretchType;
            $this->mode = (string) $reportElement['mode'] ?: $this->mode;
            $this->key = (string) $reportElement['key'] ?: $this->key;
            $this->isPrintWhenDetailOverflows = (bool) $reportElement['isPrintWhenDetailOverflows'] ?: $this->isPrintWhenDetailOverflows;
    
            $this->foreColor = new RGBColor($reportElement['forecolor'] ?? '#000000');
            $this->backColor = new RGBColor($reportElement['backcolor'] ?? '#FFFFFF');
        }
       
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
