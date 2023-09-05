<?php

namespace Thermiteplasma\Phusion\Elements;

class Paragraph
{
    public string $lineSpacing = 'Single';
    public float $lineSpacingSize = 1.0;

    public int $firstLineIndex = 0;
    public int $leftIndent = 0;
    public int $rightIndent = 0;
    public int $spacingBefore = 0;
    public int $spacingAfter = 0;
    public int $tabStopWidth = 0;

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }

    public function __construct($element = null)
    {
        if ($element) {
            $this->lineSpacing = $element->lineSpacing ?: $this->lineSpacing;
            $this->lineSpacingSize = $element->lineSpacingSize ?: $this->lineSpacingSize;
            $this->firstLineIndex = $element->firstLineIndex ?: $this->firstLineIndex;
            $this->leftIndent = $element->leftIndent ?: $this->leftIndent;
            $this->rightIndent = $element->rightIndent ?: $this->rightIndent;
            $this->spacingBefore = $element->spacingBefore ?: $this->spacingBefore;
            $this->spacingAfter = $element->spacingAfter ?: $this->spacingAfter;
            $this->tabStopWidth = $element->tabStopWidth ?: $this->tabStopWidth;
        }
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
