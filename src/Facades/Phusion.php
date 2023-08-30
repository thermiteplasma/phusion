<?php

namespace Thermiteplasma\Phusion\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thermiteplasma\Phusion\Phusion
 */
class Phusion extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Thermiteplasma\Phusion\Phusion::class;
    }
}
