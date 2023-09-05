<?php

namespace Thermiteplasma\Phusion\Elements\ElementConcerns;

use Thermiteplasma\Phusion\Elements\Pen;

Trait WithPen
{
    public Pen $pen;

    public function pen(Pen $pen): static
    {
        $this->pen = $pen;
        return $this;
    }
}
