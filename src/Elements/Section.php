<?php

namespace Thermiteplasma\Phusion\Elements;

use Thermiteplasma\Phusion\Enums\SplitType;
use Thermiteplasma\Phusion\Elements\ElementConcerns\ContainsChildElements;

class Section
{
    use ContainsChildElements;

    public int $height = 0;
    public SplitType $splitType = SplitType::STRETCH;

    public function __construct($band)
    {
        // $band = $section->band;

        $this->height = (int) $band['height'];
        
        $splitType = (string) $band["splitType"] ?: 'Stretch';
        $this->splitType = SplitType::tryFrom($splitType);

        $this->processChildren($band);
    }

}
?>