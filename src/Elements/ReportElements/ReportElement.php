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

    public function __construct($element)
    {
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

    public function shouldFill(): bool
    {
        return $this->mode === 'Opaque';
    }
}
