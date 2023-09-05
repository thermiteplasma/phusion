<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Closure;
use Thermiteplasma\Phusion\Elements\Box;
use Thermiteplasma\Phusion\Elements\Pen;
use Thermiteplasma\Phusion\Enums\VerticalAlignment;
use Thermiteplasma\Phusion\Enums\HorizontalAlignment;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithBox;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithPen;

class Image extends ReportElement
{
    use WithBox;
    use WithPen;

    public string $imageExpression = '';
    public string $scaleImage = 'RealSize';

    public HorizontalAlignment $hAlign = HorizontalAlignment::LEFT;
    public VerticalAlignment $vAlign = VerticalAlignment::TOP;

    public function __construct($element = null)
    {
        $this->box = new Box($element?->box);
        $this->pen = new Pen($element?->graphicElement?->pen);

        if (isset($element->imageExpression)) {
            $this->imageExpression = $element->imageExpression;
        }

        if (isset($element->scaleImage)) {
            $this->scaleImage = $element->scaleImage;
        }

        if (isset($element["hAlign"])) {
            $this->hAlign = HorizontalAlignment::tryFrom((string) $element["hAlign"]);
        }

        if (isset($element["vAlign"])) {
            $this->vAlign = VerticalAlignment::tryFrom((string) $element["vAlign"]);
        }

        parent::__construct($element);
    }

    public function getFitbox()
    {
        if ($this->scaleImage == 'FillFrame') {
            return false;
        }

        $hAlign = substr($this->hAlign->value, 0, 1);
        $vAlign = substr($this->vAlign->value, 0, 1);

        return $hAlign . $vAlign;
    }

    public function imageExpression(string | Closure $imageExpression): static
    {
        $this->imageExpression = $imageExpression;
        return $this;
    }

    public function scaleImage(string $scaleImage): static
    {
        $this->scaleImage = $scaleImage;
        return $this;
    }

    public function hAlign(HorizontalAlignment $hAlign): static
    {
        $this->hAlign = $hAlign;
        return $this;
    }

    public function vAlign(VerticalAlignment $vAlign): static
    {
        $this->vAlign = $vAlign;
        return $this;
    }

}
