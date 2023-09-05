<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Closure;
use Thermiteplasma\Phusion\Elements\Box;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithBox;
use Thermiteplasma\Phusion\Enums\VerticalAlignment;
use Thermiteplasma\Phusion\Enums\HorizontalAlignment;

class Image extends GraphicElement
{
    use WithBox;

    public string $direction = 'TopDown';

    public string $imageExpression = '';
    public string $scaleImage = 'RealSize';

    public HorizontalAlignment $hAlign = HorizontalAlignment::LEFT;
    public VerticalAlignment $vAlign = VerticalAlignment::TOP;

    public function __construct($element)
    {
        $this->box = new Box($element->box);
        
        $this->imageExpression = $element->imageExpression;
        $this->scaleImage = (string) $element['scaleImage'] ?: $this->scaleImage;

        $hAlign = (string) $element["hAlign"] ?: 'Left';
        $this->hAlign = HorizontalAlignment::tryFrom($hAlign);

        $vAlign = (string) $element["vAlign"] ?: 'Top';
        $this->vAlign = VerticalAlignment::tryFrom($vAlign);
        
        if (!file_exists($this->imageExpression)) {
            ray('Could not find image at', $this->imageExpression)->red();
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
