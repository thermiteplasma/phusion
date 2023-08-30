<?php

namespace Thermiteplasma\Phusion\Elements\ReportElements;

use Thermiteplasma\Phusion\Elements\ReportElements\Table\Column;

class Table extends ReportElement
{
    public array $columns = [];

    public function __construct($element)
    {
        parent::__construct($element);

        $table = $element->children('jr', true)->table;
        
        foreach($table->column as $column) {
            $this->columns[] = new Column($column);
        }
    }
    
}
