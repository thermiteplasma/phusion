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

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }

    // public function __construct($name, $groupExpression) {
    //     $this->name = $name;
    //     $this->groupExpression = $groupExpression;
    // }

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

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function groupExpression(Closure $groupExpression): static
    {
        $this->groupExpression = $groupExpression;
        return $this;
    }

    public function addHeader(Section $section): static
    {
        $this->headers[] = $section;
        return $this;
    }

    public function addFooter(Section $section): static
    {
        $this->footers[] = $section;
        return $this;
    }

    public function headers(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    public function footers(array $footers): static
    {
        $this->footers = $footers;
        return $this;
    }
}
