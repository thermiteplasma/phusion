<?php

namespace Thermiteplasma\Phusion\Elements\ElementConcerns;

use Thermiteplasma\Phusion\Elements\Font;
use Thermiteplasma\Phusion\Enums\Rotation;
use Thermiteplasma\Phusion\Enums\VerticalAlignment;
use Thermiteplasma\Phusion\Enums\HorizontalAlignment;

Trait WithTextElement
{
    public HorizontalAlignment $textAlignment = HorizontalAlignment::LEFT;
    
    public VerticalAlignment $verticalAlignment = VerticalAlignment::TOP;
    
    public Rotation $rotation = Rotation::NONE;
    
    public Font $font;
    
    //Paragraph Options
    public string $lineSpacing = 'Single';
    public float $lineSpacingSize = 1.0;
    public int $firstLineIndex = 0;
    public int $leftIndent = 0;
    public int $rightIndent = 0;
    public int $spacingBefore = 0;
    public int $spacingAfter = 0;
    public int $tabStopWidth = 0;


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

        if (isset($element->paragraph)) {
            $this->lineSpacing = $element->paragraph->lineSpacing ?: $this->lineSpacing;
            $this->lineSpacingSize = $element->paragraph->lineSpacingSize ?: $this->lineSpacingSize;
            $this->firstLineIndex = $element->paragraph->firstLineIndex ?: $this->firstLineIndex;
            $this->leftIndent = $element->paragraph->leftIndent ?: $this->leftIndent;
            $this->rightIndent = $element->paragraph->rightIndent ?: $this->rightIndent;
            $this->spacingBefore = $element->paragraph->spacingBefore ?: $this->spacingBefore;
            $this->spacingAfter = $element->paragraph->spacingAfter ?: $this->spacingAfter;
            $this->tabStopWidth = $element->paragraph->tabStopWidth ?: $this->tabStopWidth;
        }
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

    public function textAlignment(HorizontalAlignment $textAlignment): static
    {
        $this->textAlignment = $textAlignment;
        return $this;
    }

    public function verticalAlignment(VerticalAlignment $verticalAlignment): static
    {
        $this->verticalAlignment = $verticalAlignment;
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

    public function getLineHeightRatio()
    {
        return match ($this->lineSpacing) {
            '1_1_2' => 1.5,
            'Double' => 1.5,
            'Proportional' => $this->lineSpacingSize,
            default => 1.0,
        };
    }

    public function lineSpacing(string $lineSpacing): static
    {
        $this->lineSpacing = $lineSpacing;
        return $this;
    }

    public function lineSpacingSize(float $lineSpacingSize): static
    {
        $this->lineSpacingSize = $lineSpacingSize;
        return $this;
    }

    public function firstLineIndex(int $firstLineIndex): static
    {
        $this->firstLineIndex = $firstLineIndex;
        return $this;
    }

    public function leftIndent(int $leftIndent): static
    {
        $this->leftIndent = $leftIndent;
        return $this;
    }

    public function rightIndent(int $rightIndent): static
    {
        $this->rightIndent = $rightIndent;
        return $this;
    }

    public function spacingBefore(int $spacingBefore): static
    {
        $this->spacingBefore = $spacingBefore;
        return $this;
    }

    public function spacingAfter(int $spacingAfter): static
    {
        $this->spacingAfter = $spacingAfter;
        return $this;
    }

    public function tabStopWidth(int $tabStopWidth): static
    {
        $this->tabStopWidth = $tabStopWidth;
        return $this;
    }
}
