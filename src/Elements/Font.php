<?php

namespace Thermiteplasma\Phusion\Elements;

class Font
{
    public int $size = 15;
    public bool $isBold = false;
    public bool $isItalic = false;
    public bool $isUnderline = false;
    
    public string $name = 'helvetica';

    public function __construct($element = null)
    {
        
        if ($element) {
            $this->size = (int) $element['size'] ?: $this->size;
            $this->isBold = (bool)$element['isBold'] ?? $this->isBold;
            $this->isItalic = (bool)$element['isItalic'] ?: $this->isItalic;
            $this->isUnderline = (bool) $element['isUnderline'] ?: $this->isUnderline;
            $this->name = (string) $element['fontName'] ?: $this->name;
        }
    }

    public function getFontStyleString()
    {
        $fontStyle = '';
        if ($this->isBold) {
            $fontStyle .= 'B';
        }

        if ($this->isItalic) {
            $fontStyle .= 'I';
        }

        if ($this->isUnderline) {
            $fontStyle .= 'U';
        }
        return $fontStyle;
    }
}
