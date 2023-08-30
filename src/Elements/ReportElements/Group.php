<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Closure;
use Thermiteplasma\Phusion\Elements\Section;

class Group
{
    public string $name = '';
    
    public array $headers = [];

    public array $footers = [];

    public Closure $groupExpression;

    public function __construct($name, $groupExpression) {
        $this->name = $name;
        $this->groupExpression = $groupExpression;
    }

    public function setup($element) {
        if (isset($element->groupHeader)) {
            foreach($element->groupHeader->children() as $object => $child) {
                $this->headers[] = new Section($child);
            }
        }

        if (isset($element->groupFooter)) {
            foreach($element->groupFooter->children() as $object => $child) {
                $this->footers[] = new Section($child);
            }
        }
    }
}
