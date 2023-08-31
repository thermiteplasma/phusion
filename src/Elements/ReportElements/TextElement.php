<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\Font;
use Thermiteplasma\Phusion\Enums\HorizontalAlignment;
use Thermiteplasma\Phusion\Enums\Rotation;
use Thermiteplasma\Phusion\Enums\TextAlignment;
use Thermiteplasma\Phusion\Enums\VerticalAlignment;

Class TextElement
{
    public HorizontalAlignment $textAlignment = HorizontalAlignment::LEFT;
    
    public VerticalAlignment $verticalAlignment = VerticalAlignment::TOP;
    
    public Rotation $rotation = Rotation::NONE;
    
    public Font $font;
    
    public Paragraph $paragraph;

    public function __construct($element = null)
    {
        $textAlignment = (string) $element["textAlignment"] ?: 'Left';
        $this->textAlignment = HorizontalAlignment::tryFrom($textAlignment);

        $verticalAlignment = (string) $element["verticalAlignment"] ?: 'Top';
        $this->verticalAlignment = VerticalAlignment::tryFrom($verticalAlignment);

        $rotation = (string) $element["rotation"] ?: 'None';
        $this->rotation = Rotation::tryFrom($rotation);

        $this->font = new Font($element->font ?? null);

        $this->paragraph = new Paragraph($element->paragraph ?? null);
    }

    public function getTextAlignment()
    {
        return substr($this->textAlignment->value, 0, 1);
    }

    public function getVerticalAlignment($overflow = false)
    {
        return substr($this->verticalAlignment->value, 0, 1);
    }

    public function rotationAngle(): int
    {
        return $this->rotation->angle();
    }
}
