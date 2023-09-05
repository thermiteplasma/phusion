<?php

namespace Thermiteplasma\Phusion\Elements\ElementConcerns;

use Thermiteplasma\Phusion\Elements\Font;
use Thermiteplasma\Phusion\Enums\Rotation;
use Thermiteplasma\Phusion\Elements\Paragraph;
use Thermiteplasma\Phusion\Enums\VerticalAlignment;
use Thermiteplasma\Phusion\Enums\HorizontalAlignment;

Trait WithTextElement
{
    public HorizontalAlignment $textAlignment = HorizontalAlignment::LEFT;
    
    public VerticalAlignment $verticalAlignment = VerticalAlignment::TOP;
    
    public Rotation $rotation = Rotation::NONE;
    
    public Font $font;
    
    public Paragraph $paragraph;

    public function setupTextElement($element = null)
    {
        if (isset($element['textAlignment'])) {
            $this->textAlignment = HorizontalAlignment::tryFrom((string) $element["textAlignment"]);
        }

        if (isset($element['verticalAlignment'])) {
            $this->verticalAlignment = VerticalAlignment::tryFrom((string) $element["verticalAlignment"]);
        }

        if (isset($element['rotation'])) {
            $this->rotation = Rotation::tryFrom((string) $element["rotation"]);
        }
        

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

    public function textAlignment(HorizontalAlignment | string $textAlignment): static
    {
        $this->textAlignment = HorizontalAlignment::tryFrom($textAlignment);
        return $this;
    }

    public function verticalAlignment(VerticalAlignment | string $verticalAlignment): static
    {
        $this->verticalAlignment = VerticalAlignment::tryFrom($verticalAlignment);
        return $this;
    }

    public function rotation(Rotation | string $rotation): static
    {
        $this->rotation = Rotation::tryFrom($rotation);
        return $this;
    }

    public function font(Font $font): static
    {
        $this->font = $font;
        return $this;
    }

    public function paragraph(Paragraph $paragraph): static
    {
        $this->paragraph = $paragraph;
        return $this;
    }
}
