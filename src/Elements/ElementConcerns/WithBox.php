<?php

namespace Thermiteplasma\Phusion\Elements\ElementConcerns;

use Thermiteplasma\Phusion\Elements\Box;

Trait WithBox
{
    public Box $box;

    public function box(Box $box): static
    {
        $this->box = $box;
        return $this;
    }
}
