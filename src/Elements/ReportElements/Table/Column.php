<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements\Table;

use Thermiteplasma\Phusion\Elements\ReportElements\Table\Cell;

class Column
{
    public ?Cell $tableHeader = null;
    public ?Cell $columnHeader = null;
    public ?DetailCell $detailCell = null;
    public ?Cell $columnFooter = null;
    public ?Cell $tableFooter = null;

    public int $width = 40;

    public function __construct($element)
    {
        $this->width = (int) $element->attributes()['width'] ?: $this->width;
        
        if (isset($element->tableHeader)) {
            $this->tableHeader = new Cell($element->tableHeader);
        }

        if (isset($element->columnHeader)) {
            $this->columnHeader = new Cell($element->columnHeader);
        }

        if (isset($element->detailCell)) {
            $this->detailCell = new DetailCell($element->detailCell);
        }

        if (isset($element->columnFooter)) {
            $this->columnFooter = new Cell($element->columnFooter);
        }

        if (isset($element->tableFooter)) {
            $this->tableFooter = new Cell($element->tableFooter);
        }
    }
}
