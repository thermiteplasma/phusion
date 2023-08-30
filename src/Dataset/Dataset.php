<?php

namespace Thermiteplasma\Phusion\Dataset;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Dataset
{
    public $fields = [];
    
    public $variables = [];

    public $groups = [];

    public $data = [];

    public $currentRow = 0;

    public $rowData = [];

    public function __construct($source, $fields = [], $variables = [], $groups = []) {
        if ($source instanceof Model) {
            
            $this->data[] = $source;
        }

        if ($source instanceof Collection) {
            $this->data = $source->toArray();
        }

        $this->fields = $fields;

        $this->variables = $variables;

        $this->groups = $groups;

        $this->rowData = $this->data[$this->currentRow];

        $this->initializeVariables();
    }

    public function initializeVariables()
    {

    }

    public function nextRow() {
        $this->currentRow++;
        
        $this->rowData = $this->data[$this->currentRow];

    }

    public function valueFor($field)
    {
        if ($field) {
            return collect($this->rowData)->pull($field->map ?? '');
        }
        return $field;
    }
}