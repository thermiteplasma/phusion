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
        return match($this->textAlignment) {
            "Center" => "C",
            "Right" => "R",
            default => "L",
        };
    }

    public function getVerticalAlignment($overflow = false)
    {
        return match($this->verticalAlignment) {
            "Middle" => "M",
            "Bottom" => "B",
            default => "T",
        };
    }

    public function rotationAngle(): int
    {
        return match($this->rotation) {
            "Left" => 90,
            "Right" => 270,
            "UpsideDown" => 180,
            default => 0,
        };
    }
}
