<?php

namespace Thermiteplasma\Phusion;

use Exception;
use Thermiteplasma\Phusion\Elements\Style;
use Thermiteplasma\Phusion\Dataset\Dataset;
use Thermiteplasma\Phusion\Elements\Section;
use Thermiteplasma\Phusion\Enums\PageOrientation;

class Report
{
    /**
     * The report name
     *
     * @var string
     */
    public string $name = '';

    /**
     * The page width in px.
     *
     * @var int
     */
    public int $pageWidth = 842;
    
    /**
     * The page height in px.
     * 
     * @var int
     */
    public int $pageHeight = 595;
    
    /**
     * The page orientation. Landscape or Portrait.
     * 
     * @var PageOrientation
     */
    public PageOrientation $orientation = PageOrientation::PORTRAIT;
    
    /**
     * The column width in px.
     * 
     * @var int
     */
    public int $columnWidth = 802;
    
    /**
     * The left margin in px.
     * 
     * @var int
     */
    public int $leftMargin = 20;
    

    /**
     * The right margin in px.
     * 
     * @var int
     */
    public int $rightMargin = 20;
    

    /**
     * The top margin in px.
     * 
     * @var int
     */
    public int $topMargin = 20;
    

    /**
     * The bottom margin in px.
     * 
     * @var int
     */
    public int $bottomMargin = 20;

    /**
     * Should the title render on it's own page.
     * 
     * @var bool
     */
    public bool $isTitleNewPage = false;
    
    /**
     * Should the summary render on it's own page.
     * 
     * @var bool
     */
    public bool $isSummaryNewPage = false;
    
    /**
     * Should the summary render with the page header and footer.
     * 
     * @var bool
     */
    public bool $isSummaryWithPageHeaderAndFooter = false;
    
    /**
     * Should the column footer float.
     * 
     * @var bool
     */
    public bool $isFloatColumnFooter = false;
    
    /**
     * Should the pagination be ignored.
     * 
     * @var bool
     */
    public bool $isIgnorePagination = false;

    /**
     * The print order. Vertical or Horizontal.
     * 
     * @var string
     */
    public string $printOrder = 'Vertical';

    /**
     * The column count.
     * 
     * @var int
     */
    public int $columnCount = 1;


    /**
     * The column spacing.
     * 
     * @var int
     */
    public int $columnSpacing = 0;


    /**
     * The report background.
     * 
     * @var Section
     */
    public ?Section $background = null;
    
    /**
     * The report title.
     * 
     * @var Section
     */
    public ?Section $title = null;
    

    /**
     * The report page header.
     * 
     * @var Section
     */
    public ?Section $pageHeader = null;
    

    /**
     * The report column header.
     * 
     * @var Section
     */
    public ?Section $columnHeader = null;
    

    /**
     * The report details.
     * 
     * @var Section[]
     */
    public array $details = [];
    
    /**
     * The report column footer.
     * 
     * @var Section
     */
    public ?Section $columnFooter = null;
    

    /**
     * The report page footer.
     * 
     * @var Section
     */
    public ?Section $pageFooter = null;
    

    /**
     * The report last page footer.
     * 
     * @var Section
     */
    public ?Section $lastPageFooter = null;


    /**
     * The report main dataset.
     * 
     * @var Dataset
     */
    public ?Dataset $mainDataset = null;


    /**
     * The report styles.
     * 
     * @var Style[]
     */
    public $styles = [];


    /**
     * The report template.
     * 
     * @var string
     */
    protected $template = '';

    /**
     * Get the report template.
     *
     * @return string
     */
    protected function getTemplate() {
        return $this->template;
    }


    public function __construct() {
        
        $template = $this->getTemplate();
        if (!$template) {
            throw new Exception("Template not set!!");
        }
        
        try {
            $jrxmlData = file_get_contents($template);
        } catch (Exception $e) {
            throw new Exception("File {$template} not found!!");
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
        $this->columnCount = (int) $jrxml["columnCount"] ?? 1;
        $this->columnSpacing = (int) $jrxml["columnSpacing"] ?? 0;
        $this->printOrder = (string) $jrxml["printOrder"]; //Vertical / Horizontal;
        
        
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


    // public function generate()
    // {
    //     $builder = new ReportBuilder($this);
   
    //     $path = resource_path('templates/test.pdf');
    //     $builder->pdf->Output($path, 'FD');
    // }

    /**
     * Get the page format.
     * 
     * @return array
     */
    public function getFormat()
    {
        $format = [$this->pageWidth, $this->pageHeight];

        if ($this->orientation == PageOrientation::LANDSCAPE) {
            $format = array_reverse($format);
        }

        return $format;
    }

}
