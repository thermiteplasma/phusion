<?php

namespace Thermiteplasma\Phusion\Elements;

class Font
{
    public int $size = 15;
    public bool $isBold = false;
    public bool $isItalic = false;
    public bool $isUnderline = false;
    
    public string $name = 'helvetica';

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }
    
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

    public function size(int $size): static
    {
        $this->size = $size;
        return $this;
    }

    public function isBold(bool $isBold): static
    {
        $this->isBold = $isBold;
        return $this;
    }

    public function isItalic(bool $isItalic): static
    {
        $this->isItalic = $isItalic;
        return $this;
    }

    public function isUnderline(bool $isUnderline): static
    {
        $this->isUnderline = $isUnderline;
        return $this;
    }

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }
}
