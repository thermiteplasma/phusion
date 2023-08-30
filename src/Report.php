<?php

namespace Thermiteplasma\Phusion;

use Exception;
use Thermiteplasma\Phusion\Elements\Style;
use Thermiteplasma\Phusion\Dataset\Dataset;
use Thermiteplasma\Phusion\Elements\Section;
use Thermiteplasma\Phusion\Enums\PageOrientation;

class Report
{
    public string $name = '';

    public int $pageWidth = 842;
    
    public int $pageHeight = 595;
    
    public PageOrientation $orientation = PageOrientation::PORTRAIT;
    
    public int $columnWidth = 802;
    
    public int $leftMargin = 20;
    
    public int $rightMargin = 20;
    
    public int $topMargin = 20;
    
    public int $bottomMargin = 20;

    public bool $isTitleNewPage = false;
    
    public bool $isSummaryNewPage = false;
    
    public bool $isSummaryWithPageHeaderAndFooter = false;
    
    public bool $isFloatColumnFooter = false;
    
    public bool $isIgnorePagination = false;

    public ?Section $background = null;
    public ?Section $title = null;
    public ?Section $pageHeader = null;
    public ?Section $columnHeader = null;
    public array $details = [];
    public ?Section $columnFooter = null;
    public ?Section $pageFooter = null;
    public ?Section $lastPageFooter = null;

    // public $jrxml;

    public ?Dataset $mainDataset = null;

    public $styles = [];

    public $template;

    public function __construct() {
        
        $jrxmlFile = $this->template;
        
        try {
            $jrxmlData = file_get_contents($jrxmlFile);
        } catch (Exception $e) {
            throw new Exception("File {$jrxmlFile} not found!!");
        }
        
        $keyword = "<queryString>
        <![CDATA[";
        
        $jrxmlData = str_replace($keyword, "<queryString><![CDATA[", $jrxmlData);

        $jrxml = simplexml_load_string($jrxmlData,null,LIBXML_NOCDATA);

        
        $this->name = (string) $jrxml["name"];
        $orientation = (string) $jrxml["orientation"] ?: 'Portrait';
        
        $this->orientation = PageOrientation::tryFrom($orientation);

        $this->pageWidth = (int) $jrxml["pageWidth"];
        $this->pageHeight = (int) $jrxml["pageHeight"];
        $this->columnWidth = (int) $jrxml["columnWidth"];
        // $this->columnCount = (int) $this->jrxml["columnCount"];
        // $this->columnNumber = 1;
        $this->leftMargin = (int) $jrxml["leftMargin"];
        $this->rightMargin = (int) $jrxml["rightMargin"];
        $this->topMargin = (int) $jrxml["topMargin"];
        $this->bottomMargin = (int) $jrxml["bottomMargin"];

        $this->isTitleNewPage = (bool) $jrxml["isTitleNewPage"];
        $this->isSummaryNewPage = (bool) $jrxml["isSummaryNewPage"];
        $this->isSummaryWithPageHeaderAndFooter = (bool) $jrxml["isSummaryWithPageHeaderAndFooter"];
        $this->isFloatColumnFooter = (bool) $jrxml["isFloatColumnFooter"];
        $this->isIgnorePagination = (bool) $jrxml["isIgnorePagination"];

        $this->mainDataset = $this->mainDataset();

        foreach ($jrxml as $reportObject => $value) {
            
            if ($reportObject == 'group') {
                $group = $this->mainDataset->groups[(string)$value['name']];
                if ($group) {
                    $group->setup($value);
                }
            }

            if ($reportObject == 'style') {
                $this->styles[(string)$value['name']] = new Style($value);
            }

            if ($reportObject == 'background') {
                $this->background = new Section($value->band);
            }

            if ($reportObject == 'title') {
                $this->title = new Section($value->band);
            }

            if ($reportObject == 'pageHeader') {
                $this->pageHeader = new Section($value->band);
            }

            if ($reportObject == 'columnHeader') {
                $this->columnHeader = new Section($value->band);
            }

            if ($reportObject == 'detail') {
                foreach($value->children() as $child) {
                    $this->details[] = new Section($child);
                }
            }

            if ($reportObject == 'columnFooter') {
                $this->columnFooter = new Section($value->band);
            }

            if ($reportObject == 'pageFooter') {
                $this->pageFooter = new Section($value->band);
            }
        }
        
        ray('REPORT', $this);
    }

    public function mainDataset(): Dataset
    {
        return new Dataset([]);
    }

    public function getStyle($key){
        if(isset($this->styles["{$key}"])){
            return $this->styles["{$key}"];
        }
    }
   
    public function generate()
    {
        $builder = new ReportBuilder($this);
   
        $path = resource_path('templates/test.pdf');
        $builder->pdf->Output($path, 'FD');
    }

    public function getFormat()
    {
        $format = [$this->pageWidth, $this->pageHeight];

        if ($this->orientation == PageOrientation::LANDSCAPE) {
            $format = array_reverse($format);
        }

        return $format;
    }

}
