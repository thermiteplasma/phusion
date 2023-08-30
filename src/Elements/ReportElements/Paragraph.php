<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

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

    public function getLineHeightRatio() {
        return match($this->lineSpacing) {
            "1_1_2" => 1.5,
            "Double" => 1.5,
            "Proportional" => $this->lineSpacingSize,
            default => 1.0,
        };
    }
}
