<?php

namespace Thermiteplasma\Phusion\Enums;

enum Rotation: string
{
    case NONE = 'None';
    case LEFT = 'Left';
    case RIGHT = 'Right';
    case UPSIDEDOWN = 'UpsideDown';

    public function angle()
    {
        return match($this) {
            Rotation::LEFT => 90,
            Rotation::RIGHT => 270,
            Rotation::UPSIDEDOWN => 180,
            default => 0,
        };
    }
}
