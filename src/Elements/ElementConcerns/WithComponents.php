<?php

namespace Thermiteplasma\Phusion\Elements\ElementConcerns;

use Thermiteplasma\Phusion\Elements\ReportElements\Line;
use Thermiteplasma\Phusion\Elements\ReportElements\Image;
use Thermiteplasma\Phusion\Elements\ReportElements\Table;
use Thermiteplasma\Phusion\Elements\ReportElements\Rectangle;
use Thermiteplasma\Phusion\Elements\ReportElements\TextField;
use Thermiteplasma\Phusion\Elements\ReportElements\StaticText;


Trait WithComponents
{
    public $components = [];
    
    public function components(array $components): static
    {
        $this->components = $components;
        return $this;
    }
    
    public function add($component)
    {
        $this->components[] = $component;
    }

    public function typeToClass($type)
    {
        return match($type) {
            'image' => Image::class,
            'rectangle' => Rectangle::class,
            'line' => Line::class,
            'staticText' => StaticText::class,
            'textField' => TextField::class,
            default => null,
        };
    }

    public function processChildren($element)
    {
        foreach($element->children() as $key => $child) {
            
            $class = $this->typeToClass($key);
            if ($class) {
                $this->add(new $class($child));
            }

            if ($key == 'componentElement') {
                $this->add(new Table($child));
            }
        }
    }
}
