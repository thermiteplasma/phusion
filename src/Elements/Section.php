<?php

namespace Thermiteplasma\Phusion\Elements;

use Thermiteplasma\Phusion\Enums\SplitType;
use Thermiteplasma\Phusion\Elements\ElementConcerns\WithComponents;

class Section
{
    use WithComponents;

    public int $height = 0;
    public SplitType $splitType = SplitType::STRETCH;

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }
    
    public function __construct($band = null)
    { 
        if (isset($band['height'])) {
            $this->height = (int) $band['height'];
        }

        if (isset($band['splitType'])) {
            $this->splitType = SplitType::tryFrom((string) $band["splitType"]);
        }
        
        if ($band) {
            $this->processChildren($band);
        }
    }

    public function height(int $height): static
    {
        $this->height = $height;
        return $this;
    }

    public function splitType(SplitType | string $splitType): static
    {
        $this->splitType = SplitType::tryFrom($splitType);
        return $this;
    }

}
?>
