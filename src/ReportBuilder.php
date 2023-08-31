<?php

namespace Thermiteplasma\Phusion;

use TCPDF;
use Thermiteplasma\Phusion\Dataset\Field;
use Thermiteplasma\Phusion\Enums\ResetType;
use Thermiteplasma\Phusion\Enums\SplitType;
use Thermiteplasma\Phusion\Dataset\Variable;
use Thermiteplasma\Phusion\Elements\Section;
use Thermiteplasma\Phusion\Enums\VariableCalculation;
use Thermiteplasma\Phusion\Elements\ReportElements\Line;
use Thermiteplasma\Phusion\Elements\ReportElements\Image;
use Thermiteplasma\Phusion\Elements\ReportElements\Table;
use Thermiteplasma\Phusion\Elements\ReportElements\Rectangle;
use Thermiteplasma\Phusion\Elements\ReportElements\TextField;
use Thermiteplasma\Phusion\Elements\ReportElements\StaticText;

class ReportBuilder
{
    public TCPDF $pdf;
    public Report $report;

    public $yAxis = 0;

    public $currentPage = 0;
    
    public $currentTablePage = 1;

    public $pageChanged = false;

    public $calculatedVariables = []; //key value pairs

    public $groupData = [];

    // public $groupData = [
    //     'Group1' => ['value' => 'A', 'count' => 1]
    // ]
    
    // public function __construct(Report $report)
    // {
    //     $this->report = $report;

    //     $format = $this->report->getFormat();

    //     $this->pdf = new TCPDF($this->report->orientation->tcpdfValue(), 'pt', $format, true);

    //     $this->pdf->SetLeftMargin($this->report->leftMargin);
    //     $this->pdf->SetRightMargin($this->report->rightMargin);
    //     $this->pdf->SetTopMargin($this->report->topMargin);
    //     $this->pdf->SetAutoPageBreak(true, $this->report->bottomMargin / 2);

    //     $this->pdf->setPrintHeader(false);
    //     $this->pdf->setPrintFooter(false);

    //     $this->calculatedVariables['REPORT_COUNT'] = 0; //initialize to 0
        
    //     $this->newPage();

    //     $this->drawTitle();

    //     $this->drawPageHeader();
    //     $this->drawColumnHeader();
        
    //     $this->drawDetail();

    //     $this->drawColumnFooter();
    //     $this->drawPageFooter();
    // }

    public function generate(Report $report)
    {
        $this->report = $report;

        $format = $this->report->getFormat();

        $this->pdf = new TCPDF($this->report->orientation->tcpdfValue(), 'pt', $format, true);

        $this->pdf->SetLeftMargin($this->report->leftMargin);
        $this->pdf->SetRightMargin($this->report->rightMargin);
        $this->pdf->SetTopMargin($this->report->topMargin);
        $this->pdf->SetAutoPageBreak(true, $this->report->bottomMargin / 2);

        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);

        $this->calculatedVariables['REPORT_COUNT'] = 0; //initialize to 0
        
        $this->newPage();

        $this->drawTitle();

        $this->drawPageHeader();
        $this->drawColumnHeader();
        
        $this->drawDetail();

        $this->drawColumnFooter();
        $this->drawPageFooter();

        return $this;
    }

    public function store($path)
    {
        $this->pdf->Output($path, 'FD');
    }

    public function download($name = 'report.pdf')
    {
        return response()->streamDownload(function () {
            $this->pdf->Output('', 'S');
        }, $name, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$name.'"'
        ]);
    }

    public function toString()
    {
        return $this->pdf->Output('', 'S');
    }

    private function newPage()
    {
        ray('NEW PAGE');
        $this->yAxis = $this->report->topMargin;
        $this->pageChanged = true;
        $this->currentPage++;
        $this->pdf->AddPage();
        $this->pdf->setPage($this->currentPage, true);

        $this->calculatedVariables['PAGE_NUMBER'] = $this->currentPage;
        $this->calculatedVariables['PAGE_COUNT'] = 0;

        $this->drawBackground();
    }

    public function generateSectionElements(Section $section, $data = [])
    {
        foreach ($section->children as $element) {
            if ($element instanceof Rectangle) {
                $this->drawRectangle($element);
            }

            if ($element instanceof Line) {
                $this->drawLine($element);
            }

            if ($element instanceof StaticText) {
                $this->drawStaticText($element);
            }

            if ($element instanceof Image) {
                $this->drawImage($element);
            }

            if ($element instanceof TextField) {
                
                $text = $this->getExpression($element->textFieldExpression);

                $this->drawTextField($element, $text);
            }

            if ($element instanceof Table) {
                $this->drawTable($element);
            }
        }
        
    }



    /**
     * Draw Sections
     */
    private function drawBackground()
    {
        if ($this->report->background) {
            //process the title section
            $this->generateSectionElements($this->report->background);
        }
    }

    private function drawTitle()
    {
        if ($this->report->title) {

            $this->generateSectionElements($this->report->title);

            if ($this->report->isTitleNewPage) {
                $this->newPage();
            } else {
                $this->yAxis += $this->report->title->height;
            }
        }
    }

    private function drawPageHeader()
    {
        if ($this->report->pageHeader) {
            
            $this->generateSectionElements($this->report->pageHeader);

            $this->setYAxis($this->report->pageHeader->height);
        }
    }

    private function drawColumnHeader()
    {
        if ($this->report->columnHeader) {
            if ($this->report->columnHeader->splitType == SplitType::STRETCH || $this->report->columnHeader->splitType == SplitType::PREVENT) {
                $this->PreventY_axis($this->report->columnHeader->height);
            }
            
            $this->generateSectionElements($this->report->columnHeader);
            $this->setYAxis($this->report->columnHeader->height);
        }
    }

    private function drawGroupHeaders()
    {
        if ($this->report->mainDataset->groups) {
            foreach($this->report->mainDataset->groups as $groupName => $group) {
                
                $groupResult = $group->groupExpression->call($this, $this->report->mainDataset->rowData);
                $currentGroupValue = $this->groupData[$groupName]['value'] ?? null;
                
                // ray('GROUP ' . $groupName, $groupResult, $currentGroup);

                if ($groupResult != $currentGroupValue) {
                    
                    // ray('GROUP '. $groupName.' CHANGED ' . $groupResult);
                    //we are starting a new group
                    $this->groupData[$groupName]['value'] = $groupResult;
                    $this->groupData[$groupName]['count'] = 1;

                    $this->calculatedVariables[$groupName . '_COUNT'] = $this->groupData[$groupName]['count'];
                    
                    foreach($group->headers as $header) {
                        
                        if ($header->splitType == SplitType::STRETCH || $header->splitType == SplitType::PREVENT) {
                            $this->PreventY_axis($header->height);
                        }
    
                        $this->generateSectionElements($header);
    
                        $this->setYAxis($header->height);
                    }
                } else {
                    // ray('INCREMENT GROUP COUNT');
                    $this->groupData[$groupName]['count']++;// = $this->groupData[$groupName]['count'] + 1;
                }

            }

            foreach($this->groupData as $groupName => $group) {
                $this->calculatedVariables[$groupName . '_COUNT'] = $group['count'];
            }

            // ray($this->calculatedVariables);
            // ray('for ' . $this->report->mainDataset->rowData['name'], $this->groupData);
        }
    }

    private function drawDetail()
    {
        if (count($this->report->details) > 0) {
            
            for ($i=0; $i < count($this->report->mainDataset->data); $i++) { 
                $this->calculatedVariables['REPORT_COUNT']++;
                $this->calculatedVariables['PAGE_COUNT']++;

                $headersDrawn = false;
                $variablesCalculated = false;
                // $this->drawGroupHeaders();
                
                foreach ($this->report->details as $detail) {
                    //we only want to calculate the variables once per detail so we need a way to track if they have been calculated for a
                    //specific row

                    if ($detail->splitType == SplitType::STRETCH || $detail->splitType == SplitType::PREVENT) {
                        $this->PreventY_axis($detail->height);
                    }

                    if (!$variablesCalculated) {
                        $this->calculateVariables();
                        $variablesCalculated = true;
                    }

                    if (!$headersDrawn) {
                        $this->drawGroupHeaders();
                        $headersDrawn = true;
                    }

                    $this->generateSectionElements($detail);

                    if ($detail->splitType == SplitType::STRETCH || $detail->splitType == SplitType::PREVENT) {
                        $this->setYAxis($detail->height);
                    }
                }

                $this->report->mainDataset->nextRow();

                //process data for group footers here
                
                
                $this->drawGroupFooters();
            }
        }
        return;
    }

    private function drawGroupFooters()
    {
        //how do we know we are at he ned of a group?

        if ($this->report->mainDataset->groups) {
            foreach(array_reverse($this->report->mainDataset->groups) as $groupName => $group) {

                $groupResult = $group->groupExpression->call($this, $this->report->mainDataset->rowData);
                
                $currentGroup = $this->groupData[$groupName]['value'] ?? null;
                // ray('GROUP', $groupResult, $currentGroup);
                if ($groupResult != $currentGroup) {
                    //end of group
                    
                    // $this->calculatedVariables[$groupName . '_COUNT'] = 0;

                    // $this->groupData[$groupName]['value'] = $groupResult;
                    
                    foreach($group->footers as $footer) {
                        
                        if ($footer->splitType == SplitType::STRETCH || $footer->splitType == SplitType::PREVENT) {
                            $this->PreventY_axis($footer->height);
                        }
    
                        $this->generateSectionElements($footer);
    
                        $this->setYAxis($footer->height);

                        //reset group variables
                    }

                    foreach($this->report->mainDataset->variables as $variableName => $variable) {
                        if ($variable->resetType == ResetType::GROUP && $variable->resetGroup == $groupName) {
                            $this->calculatedVariables[$variableName] = $variable->initialValue;
                        }
                    }

                }

                
                // foreach($group->footers as $footer) {
                    
                //     if ($footer->splitType == SplitType::STRETCH || $footer->splitType == SplitType::PREVENT) {
                //         $this->PreventY_axis($footer->height);
                //     }
                    
                //     $this->generateSectionElements($footer);
                    
                //     $this->setYAxis($footer->height);
                // }
            }
        }
    }

    private function drawColumnFooter()
    {
        if ($this->report->columnFooter) {
            if ($this->report->columnFooter->splitType == SplitType::STRETCH || $this->report->columnFooter->splitType == SplitType::PREVENT) {
                $this->PreventY_axis($this->report->columnFooter->height);
            }
            
            $this->generateSectionElements($this->report->columnFooter);
            $this->setYAxis($this->report->columnFooter->height);
            
        }
    }

    private function drawPageFooter()
    {
        // ray('DRAW PAGE FOOTER');
        if ($this->report->pageFooter) {
            $this->yAxis = $this->report->topMargin;

            $this->setYAxis($this->report->pageHeight - $this->report->topMargin - $this->report->pageFooter->height - $this->report->bottomMargin);

            $this->generateSectionElements($this->report->pageFooter, $this->report->mainDataset->rowData);

            $this->setYAxis($this->report->pageFooter->height);
        }
    }


    /**
     * Draw individual elements
     */

    private function drawRectangle(Rectangle $rectangle)
    {
        if ($rectangle->mode == 'Opaque') {
            $fillcolor = $rectangle->backColor->toArray();
        } else {
            $fillcolor = ['r' => 255, 'g' => 255, 'b' => 255];
        }

        // if ($data['mode'] == 'Transparent') {
        //     $style = '';
        // } else {
        //     $style = 'FD';
        // }
        $style = 'FD';

        $this->pdf->RoundedRect(x: $rectangle->x + $this->report->leftMargin, y: $rectangle->y + $this->yAxis, w: $rectangle->width, h: $rectangle->height, r: $rectangle->radius, round_corner: '1111', style: $style, border_style: ['all' => $rectangle->pen->border()], fill_color: $fillcolor);

        // if (isset($rectangle->pen->border()['width']) && $rectangle->pen->border()['width'] > 0) {

        //     $this->pdf->SetLineStyle($rectangle->pen->border());

        //     $this->pdf->RoundedRect(
        //         x: $rectangle->x + $this->report->leftMargin,
        //         y: $rectangle->y + $this->yAxis,
        //         w: $rectangle->width,
        //         h: $rectangle->height,
        //         r: $rectangle->radius,
        //         round_corner: '1111',
        //         border_style: ['all' => $rectangle->pen->border()]
        //     );

        //     $this->pdf->SetLineStyle([]);
        // }
    }

    private function drawLine(Line $line)
    {
        $linewidth = $line->pen->lineWidth ?? 0;

        $dash = $line->pen->dash();

        $drawcolor = $line->foreColor->toArray();

        $style = [
            'color' => $drawcolor,
            'width' => $linewidth,
            'dash' => $dash,
        ];

        if ($line->direction == 'TopDown') {
            $this->pdf->Line(x1: $line->x + $this->report->leftMargin, y1: $line->y + $this->yAxis, x2: $line->x + $line->width + $this->report->leftMargin, y2: $line->y + $line->height + $this->yAxis, style: $style);
        } else {
            $this->pdf->Line(x1: $line->x + $this->report->leftMargin, y1: $line->y + $line->height + $this->yAxis, x2: $line->x + $line->width + $this->report->leftMargin, y2: $line->y + $this->yAxis, style: $style);
        }

        //do we need to reset the colors here
        $this->pdf->setDrawColor(0, 0, 0);
        $this->pdf->setFillColor(255, 255, 255);
    }

    public function drawStaticText(StaticText $staticText, $attributes = [])
    {
        
        $textColor = $staticText->foreColor;
        $fillColor = $staticText->backColor;

        $stretchOverflow = false;
        $printOverflow = false;


        //this is only for a text field type
        // if (isset($data["isStretchWithOverflow"]) && $data["isStretchWithOverflow"] == "true") {
        //     $stretchoverflow = "true";
        // }

        if ($staticText->isPrintWhenDetailOverflows) {
            $printOverflow = true;
            $stretchOverflow = false;
        }

        $align = $staticText->textElement->getTextAlignment();
        $valign = $staticText->textElement->getVerticalAlignment();

        //we need to set rotation
        // if (isset($data->textElement["rotation"])) {
        //     $rotation = $data->textElement["rotation"];
        // }

        // if (isset($data->textElement->font["fontName"])) {

        //     //else
        //     //$data->text=$data->textElement->font["pdfFontName"];//$this->recommendFont($data->text);
        //     $font = $this->recommendFont($data->text, $data->textElement->font["fontName"], $data->textElement->font["pdfFontName"]);
        // }
        $fontsize = $staticText->textElement->font->size;
        $fontstyle = $staticText->textElement->font->getFontStyleString();

        // if (isset($data->reportElement["key"]) && !empty($data->reportElement["key"])) {
        //     $height = $fontsize * $this->adjust;
        // }

        $lineHeightRatio = $staticText->textElement->paragraph->getLineHeightRatio();

        $this->pdf->setCellHeightRatio($lineHeightRatio);
        
        $x = isset($attributes['x']) ? $attributes['x'] : $staticText->x + $this->report->leftMargin;

        $this->pdf->setXY($x, $staticText->y);

        $this->pdf->setTextColor($textColor->red, $textColor->green, $textColor->blue);
        $this->pdf->setDrawColor(0, 0, 0); //What is this for
        $this->pdf->setFillColor($fillColor->red, $fillColor->green, $fillColor->blue);
        $this->pdf->setFont($staticText->textElement->font->name, $fontstyle, $fontsize);

        
        //CHECK OVERFLOW METHOD. THIS IS REUSABLE PER COMPONENT?

        $this->pdf->setXY($x, $staticText->y + $this->yAxis);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        // $x = $staticText->x;
        // $y = $staticText->y + $this->report->topMargin;
        //add padding to cell
        $this->pdf->setCellPaddings($staticText->box->leftPadding, $staticText->box->topPadding, $staticText->box->rightPadding, $staticText->box->bottomPadding);

        $w = $staticText->width;
        $h = isset($attributes['height']) ? $attributes['height'] : $staticText->height;
        

        $this->pdf->StartTransform();

        $clipx = $x;
        $clipy = $staticText->y + $this->yAxis;
        $clipw = $staticText->width;
        $cliph = $staticText->height;

        $rotated = false;

        //handle rotation
        $angle = $staticText->textElement->rotationAngle();

        if ($angle > 0) {
            $this->pdf->Rect($clipx, $clipy, $clipw, $cliph, 'CNZ');
            $this->pdf->Rotate($angle);
            $rotated = true;
            switch ($angle) {
                case 90:
                    $x = $x - $staticText->height;
                    $h = $staticText->width;
                    $w = $staticText->height;
                    break;
                case 180:
                    $x = $x - $staticText->width;
                    $y = $y - $staticText->height;
                    break;
                case 270:
                    $y = $y - $staticText->width;
                    $h = $staticText->width;
                    $w = $staticText->height;
                    break;
            }
        }

        if ($printOverflow || $stretchOverflow) {
            $x = $this->pdf->GetX();
            $yAfter = $this->pdf->GetY();
            $maxheight = 0; //array_key_exists('maxheight', $arraydata) ? $arraydata['maxheight'] : '';
            //if($arraydata["link"])   echo $arraydata["linktarget"].",".$arraydata["link"]."<br/><br/>";
            $this->pdf->MultiCell($w, $h, $staticText->text, $staticText->box->getBorders(), $align, $staticText->shouldFill(), 1, $x, $y, true, 0, false, true, $maxheight); //,$arraydata["valign"]);
            if ($yAfter + $staticText->height <= $this->report->pageHeight) {
                $this->yAxis = $this->pdf->GetY() - 20;
            }

            // clip width & height
            if (!$rotated) {
                $this->pdf->Rect($clipx, $clipy, $clipw, $cliph, 'CNZ');
            }

            $border = $staticText->box->getBorders();
            // ray('draw text at 1', $staticText->text, $x, $y, $this->yAxis);
            $this->pdf->MultiCell($w, $h, $staticText->text, $border, $align, $staticText->shouldFill(), 0, $x, $y, true, 0, false, true, $h, $valign);
            // if (isset($arraydata['link']) && !empty($arraydata['link'])) {
            //     $pdf->Link($x, $y, $arraydata['width'], $arraydata['height'], $arraydata['link']);
            // }
        } else {
            // clip width & height
            if (!$rotated) {
                $this->pdf->Rect($clipx, $clipy, $clipw, $cliph, 'CNZ');
            }

            $this->pdf->MultiCell($w, $h, $staticText->text, $staticText->box->getBorders(), $align, $staticText->shouldFill(), 0, $x, $y, true, 0, false, true, $h, $valign);
            // if (isset($arraydata['link']) && !empty($arraydata['link'])) {
            //     $pdf->Link($x, $y, $arraydata['width'], $arraydata['height'], $arraydata['link']);
            // }
        }

        $this->pdf->StopTransform();
    }

    public function drawTextField(TextField $textField, $text)
    {
        
        $textColor = $textField->foreColor;
        $fillColor = $textField->backColor;

        $stretchOverflow = false;
        $printOverflow = false;

        //this is only for a text field type
        if ($textField->isStretchWithOverflow) {
            $stretchOverflow = 'true';
        }

        if ($textField->isPrintWhenDetailOverflows) {
            $printOverflow = true;
            $stretchOverflow = false;
        }

        $align = $textField->textElement->getTextAlignment();
        $valign = $textField->textElement->getVerticalAlignment();

        // if (isset($data->textElement->font["fontName"])) {

        //     //else
        //     //$data->text=$data->textElement->font["pdfFontName"];//$this->recommendFont($data->text);
        //     $font = $this->recommendFont($data->text, $data->textElement->font["fontName"], $data->textElement->font["pdfFontName"]);
        // }
        $fontsize = $textField->textElement->font->size;
        $fontstyle = $textField->textElement->font->getFontStyleString();

        // if (isset($data->reportElement["key"]) && !empty($data->reportElement["key"])) {
        //     $height = $fontsize * $this->adjust;
        // }

        $lineHeightRatio = $textField->textElement->paragraph->getLineHeightRatio();

        $this->pdf->setCellHeightRatio($lineHeightRatio);

        // $this->pdf->setXY($staticText->x, $staticText->y);

        $this->pdf->setTextColor($textColor->red, $textColor->green, $textColor->blue);
        $this->pdf->setDrawColor(0, 0, 0); //What is this for
        $this->pdf->setFillColor($fillColor->red, $fillColor->green, $fillColor->blue);

        $this->pdf->setFont($textField->textElement->font->name, $fontstyle, $fontsize);

        //CHECK OVERFLOW METHOD. THIS IS REUSABLE PER COMPONENT?

        $this->pdf->setXY($textField->x + $this->report->leftMargin, $textField->y + $this->yAxis);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        // $x = $staticText->x;
        // $y = $staticText->y + $this->report->topMargin;
        //add padding to cell
        $this->pdf->setCellPaddings($textField->box->leftPadding, $textField->box->topPadding, $textField->box->rightPadding, $textField->box->bottomPadding);

        $w = $textField->width;
        $h = $textField->height;

        $this->pdf->StartTransform();

        $clipx = $textField->x + $this->report->leftMargin;
        $clipy = $textField->y + $this->yAxis;
        $clipw = $textField->width;
        $cliph = $textField->height;

        $rotated = false;

        //handle rotation
        $angle = $textField->textElement->rotationAngle();

        if ($angle > 0) {
            $this->pdf->Rect($clipx, $clipy, $clipw, $cliph, 'CNZ');
            $this->pdf->Rotate($angle);
            $rotated = true;
            switch ($angle) {
                case 90:
                    $x = $x - $textField->height;
                    $h = $textField->width;
                    $w = $textField->height;
                    break;
                case 180:
                    $x = $x - $textField->width;
                    $y = $y - $textField->height;
                    break;
                case 270:
                    $y = $y - $textField->width;
                    $h = $textField->width;
                    $w = $textField->height;
                    break;
            }
        }

        if ($printOverflow || $stretchOverflow) {
            $x = $this->pdf->GetX();
            $yAfter = $this->pdf->GetY();
            $maxheight = 0; //array_key_exists('maxheight', $arraydata) ? $arraydata['maxheight'] : '';
            //if($arraydata["link"])   echo $arraydata["linktarget"].",".$arraydata["link"]."<br/><br/>";
            $this->pdf->MultiCell($w, $h, $text, $textField->box->getBorders(), $align, $textField->shouldFill(), 1, $x, $y, true, 0, false, true, $maxheight); //,$arraydata["valign"]);
            if ($yAfter + $textField->height <= $this->report->pageHeight) {
                $this->yAxis = $this->pdf->GetY() - 20;
            }

            // clip width & height
            if (!$rotated) {
                $this->pdf->Rect($clipx, $clipy, $clipw, $cliph, 'CNZ');
            }

            $border = $textField->box->getBorders();

            $this->pdf->MultiCell($w, $h, $text, $border, $align, $textField->shouldFill(), 0, $x, $y, true, 0, false, true, $h, $valign);
            // if (isset($arraydata['link']) && !empty($arraydata['link'])) {
            //     $pdf->Link($x, $y, $arraydata['width'], $arraydata['height'], $arraydata['link']);
            // }
        } else {
            // clip width & height
            if (!$rotated) {
                $this->pdf->Rect($clipx, $clipy, $clipw, $cliph, 'CNZ');
            }

            $this->pdf->MultiCell($w, $h, $text, $textField->box->getBorders(), $align, $textField->shouldFill(), 0, $x, $y, true, 0, false, true, $h, $valign);
            // if (isset($arraydata['link']) && !empty($arraydata['link'])) {
            //     $pdf->Link($x, $y, $arraydata['width'], $arraydata['height'], $arraydata['link']);
            // }
        }

        $this->pdf->StopTransform();
    }

    public function drawImage(Image $image)
    {
        $path = $image->imageExpression;

        $imageType = substr($path, -3);

        if ($imageType == 'jpg') {
            $imageType = 'JPEG';
        } elseif ($imageType == 'png' || $imageType == 'PNG') {
            $imageType = 'PNG';
        }

        $this->pdf->Image($path, $image->x + $this->report->leftMargin, $image->y + $this->yAxis, $image->width, $image->height, $imageType, '', 'T', false, 300, '', false, false, $image->box->getBorders(), $image->getFitbox());
    }

    public function drawTable(Table $table)
    {
        

        $x = $table->x + $this->report->leftMargin;
        // $y = $table->y;

        
        $this->pdf->Ln(0);

        $this->setYAxis($table->y);
        
        $showColumnHeader = true;
        //after font definition
        $fontDefault = [];
        $fontDefault['font'] = $this->pdf->getFontFamily();
        $fontDefault['fontstyle'] = $this->pdf->getFontStyle();
        $fontDefault['fontsize'] = $this->pdf->getFontSize();

        $this->currentTablePage = $this->currentPage;

        $rowIndex = 0;
        
        $customers = \App\Models\Customer::limit(50)->get();
        foreach ($customers as $customer) {
            
            $rowIndex++;

            //get height header and detail
            $columnHeaderHeight = 0;
            $detailHeight = 0;
            $columnFooterHeight = 0;
            $tableFooterHeight = 0;

            foreach ($table->columns as $column) {

                if ($column->columnFooter->height  > $columnFooterHeight) {
                    $columnFooterHeight = $column->columnFooter->height;
                }

                if ($column->tableFooter->height > $tableFooterHeight) {
                    $tableFooterHeight = $column->tableFooter->height;
                }

                if ($column->columnHeader->height > $columnHeaderHeight) {
                    $columnHeaderHeight = $column->columnHeader->height;
                }
                //get max height
                if (count($column->columnHeader->children) > 0) {
                    
                    //children can only be staticText

                    foreach ($column->columnHeader->children as $child) {
                        $font = $child->textElement->font;
                        $this->pdf->setFont($font->name, $font->getFontStyleString(), $font->size);
                        
                        $heightNew = $this->pdf->getStringHeight($column->width, $child->text) * 1.5;
                        
                        if ($heightNew > $columnHeaderHeight) {
                            $columnHeaderHeight = $heightNew;
                        }
                    }

                } //final max height header ============================

                if ($column->detailCell->height > $detailHeight) {
                    $detailHeight = $column->detailCell->height;
                }
                
                foreach($column->detailCell->children as $child) {
                    //get line spacing
                    $lineHeightRatio = $child->textElement->paragraph->getLineHeightRatio();
                    
                    $font = $child->textElement->font;
                    $this->pdf->setFont($font->name, $font->getFontStyleString(), $font->size);
                    
                    $heightNew = $this->pdf->getStringHeight($column->width, $child->text) * $lineHeightRatio;
                    
                    if ($heightNew > $detailHeight) {
                        $detailHeight = $heightNew;
                    }
                }
            } //end get height row header and detail

            //check new page
            $this->PreventY_axis($detailHeight);
            
            //new page?
            if ($this->currentTablePage != $this->currentPage) {
                $showColumnHeader = true; //repeat columnHeader
                $this->pdf->Ln(0);
                $y = $this->yAxis;
            }

            $startX = $this->pdf->GetX();
            $startY = $this->yAxis;
            $y = $startY;
            $x = $startX;

            // ray('START X, Y', $x, $y, $this->yAxis);

            
            //design tableHeader ===================
            if ($rowIndex == 1) {
                $i = 0;
                foreach ($table->columns as $column) {
                    $i++;
                    // ray('processing table header col ' . $i);
                    // ray('pos', $x, $y);
                    
                    
                    $style = $this->report->styles[$column->tableHeader->style];

                    $this->pdf->RoundedRect(x: $x, y: $y, w: $column->width, h: $column->tableHeader->height, r: 0, round_corner: '1111', style: 'FD', border_style: ['all' => $style->box->getBorders()], fill_color: $style->backColor->toArray());

                    foreach($column->tableHeader->children as $child) {
                        if ($child instanceof StaticText) {
                            //we need to override the positioning of the static text
                            $attributes = ['x' => $x, 'height' => $column->tableHeader->height];

                            $this->drawStaticText($child, $attributes, []);
                        }
                    }
                    
                    $this->pdf->SetX($x);
                    
                    $x = $x + $column->width;
                    
                    $this->pdf->SetX($x);
                } //end column

                //start line
                $this->pdf->Ln(0);
                $x = $startX;
                $y = $y + $column->tableHeader->height;
                $this->pdf->SetX($x);
                $this->setYAxis($column->tableHeader->height);
            } //end tableHeader

            //design columnHeader table ===================
            if ($showColumnHeader) {
                foreach ($table->columns as $column) {
                    
                    $style = $this->report->styles[$column->columnHeader->style];

                    $this->pdf->RoundedRect(x: $x, y: $y, w: $column->width, h: $column->columnHeader->height, r: 0, round_corner: '1111', style: 'FD', border_style: ['all' => $style->box->getBorders()], fill_color: $style->backColor->toArray());

                    foreach ($column->columnHeader->children as $child) {
                        if ($child instanceof StaticText) {
                            $attributes = [
                                'x' => $x,
                                'height' => $column->columnHeader->height,
                            ];

                            $this->drawStaticText($child, $attributes);
                        }
                    }
                    
                    $this->pdf->SetX($x);
                    $x = $x + $column->width;
                    $this->pdf->SetX($x);
                } //end column each design header

                //start line
                $this->pdf->Ln(0);
                $x = $startX;
                $y = $y + $columnHeaderHeight;
                $this->pdf->SetX($x);
                $this->setYAxis($columnHeaderHeight);
                
                $showColumnHeader = false;
            } //final header table

            //designer detail table ===================
            foreach ($table->columns as $column) {
                $style = $this->report->styles[$column->detailCell->style];
                
                $this->pdf->RoundedRect(x: $x, y: $y, w: $column->width, h: $detailHeight, r: 0, round_corner: '1111', style: 'FD', border_style: ['all' => $style->box->getBorders()], fill_color: $style->backColor->toArray());

                foreach ($column->detailCell->children as $child) {
                    if ($child instanceof StaticText) {
                        
                        $attributes = ['x' => $x, 'height' => $column->detailCell->height];

                        $this->drawStaticText($child, $attributes);
                    }
                }

                $this->pdf->SetX($x);
                $x = $x + $column->width;
                $this->pdf->SetX($x);
            } //end column each design detail

            //start line
            $x = $startX;
            $y = $y + $detailHeight;
            $this->pdf->SetX($x);
            $this->setYAxis($detailHeight);
            $this->pdf->Ln(0);
        } //end data each

        //check new page
        if ($columnFooterHeight > 0) {
            //check new page
            $this->PreventY_axis($columnFooterHeight);

            //new page?
            if ($this->currentTablePage != $this->currentPage) {
                $this->currentTablePage = $this->currentPage;
                $this->pdf->Ln(0);
                $y = $this->yAxis;
            }
        }

        //columnFooter
        foreach ($table->columns as $column) {
            
            if ($column->columnFooter) {
                $style = $this->report->styles[$column->columnFooter->style];
                
                $this->pdf->RoundedRect(x: $x, y: $y, w: $column->width, h: $columnFooterHeight, r: 0, round_corner: '1111', style: 'FD', border_style: ['all' => $style->box->getBorders()], fill_color: $style->backColor->toArray());

                foreach ($column->columnFooter->children as $child) {
                    if ($child instanceof StaticText) {
                        $attributes = [
                            'x' => $x,
                            'height' => $column->columnFooter->height,
                        ];

                        $this->drawStaticText($child, $attributes);
                    }
                }

                

                $this->pdf->SetX($x);
                
                $x = $x + $column->width;
                $this->pdf->SetX($x);
            } else {
                break;
            }
        }
        //new line start
        $y = $y + $columnFooterHeight;
        $x = $startX;
        $this->pdf->SetX($x);
        $this->setYAxis($columnFooterHeight);
        
        //check new page
        if ($tableFooterHeight > 0) {
            //check new page
            $this->PreventY_axis($tableFooterHeight);
            
            //new page?
            if ($this->currentTablePage != $this->currentPage) {
                $this->currentTablePage = $this->currentPage;
                $this->pdf->Ln(0);
                $y = $this->yAxis;
            }
        }

        //tableFooter
        foreach ($table->columns as $column) {
            
            if ($column->tableFooter) {
                
                $style = $this->report->styles[$column->tableFooter->style];

                $this->pdf->RoundedRect(x: $x, y: $y, w: $column->width, h: $tableFooterHeight, r: 0, round_corner: '1111', style: 'FD', border_style: ['all' => $style->box->getBorders()], fill_color: $style->backColor->toArray());

                foreach ($column->tableFooter->children as $child) {
                    if ($child instanceof StaticText) {
                        $attributes = [
                            'x' => $x,
                            'height' => $column->tableFooter->height,
                        ];

                        $this->drawStaticText($child, $attributes);
                    }
                }

                $this->pdf->SetX($x);
                
                $x = $x + $column->width;
                $this->pdf->SetX($x);
            } else {
                break;
            }
        }
        $y = $y + $tableFooterHeight;
        $x = $startX;
        $this->pdf->SetX($x);
        $this->setYAxis($tableFooterHeight + 10);
    }



    private function setYAxis($y)
    {
        if ($this->yAxis + $y <= $this->report->pageHeight) {
            $this->yAxis = $this->yAxis + $y;
        }
    }

    public function PreventY_axis($y)
    {
        $preventY_axis = $this->yAxis + $y;

        $pageFooterHeight = $this->report->pageFooter->height ?? 0;
        $topMargin = $this->report->topMargin;
        $bottomMargin = $this->report->bottomMargin;
        $discount = $this->report->pageHeight - $pageFooterHeight - $topMargin - $bottomMargin; //dicount heights of page parts;
        
        if ($preventY_axis >= $discount) {
            if ($this->report->pageFooter) {
                // JasperPHP\Instructions::$lastPageFooter = false;
                $this->drawPageFooter();
            }

            $this->newPage();
            $this->drawPageHeader();
            $this->drawColumnHeader();

        }
    }





    // public function getExpression($text, $row, $writeHTML = null, $element = null) {
    public function getExpression($expression, $writeHTML = null) {
        
        $text = $expression;

        preg_match_all("/V{(\w+)}/", $expression, $variableMatches);
        if ($variableMatches) {
            foreach ($variableMatches[1] as $variableName) {
                $variable = $this->calculatedVariables[$variableName] ?? null;
                if ($variable) {
                    $text = $this->getVariableValue($variableName, $expression, $writeHTML);
                }
            }
        }
        
        preg_match_all("/F{[^}]*}/", $text, $fieldMatches);
        if ($fieldMatches) {
            foreach ($fieldMatches[0] as $match) {
                
                $match = str_ireplace(['F{', '}'], '', $match);
                
                $field = $this->report->mainDataset->fields[$match];

                $text = $this->getFieldValue($field, $writeHTML);
            }
        }

        return $text;
    }

    // public function getVariableValue($variable, $text, $htmlentities = false, $element = null) {
    public function getVariableValue(string $variableName, string $expression, $htmlentities = false) {
        
        $currentValue = $this->calculatedVariables[$variableName] ?? null;
        return str_ireplace(array('$V{' . $variableName . '}'), [$currentValue], $expression);
    }

    // public function getFieldValue($field, $row, $text, $htmlentities = false) {
    public function getFieldValue(Field $field, $htmlentities = false) {
        return $this->report->mainDataset->valueFor($field);
    }

    public function calculateVariables() {
        
        // ray('calculate variables for ' . $this->report->mainDataset->rowData['name'] . ' - ' . $this->report->mainDataset->rowData['machines']);
        

        foreach ($this->report->mainDataset->variables as $name => $variable) {
            $this->variableCalculation($name, $variable);
        }
        
        // ray('variable1 ' , $this->calculatedVariables['variable2']);

        if($this->pageChanged == true){
            $this->pageChanged = false;
        }
    }

    public function variableCalculation(string $name, Variable $variable) {
        // ray('calculate the value of ' . $name);
        
        $currentValue = $this->calculatedVariables[$name] ?? null;
        
        $result = $variable->variableExpression->call($this, $this->report->mainDataset->rowData);
        
        $value = $result;
        
        
        switch ($variable->calculation) {
            case VariableCalculation::SUM:
                $value += is_numeric($currentValue) ? $currentValue : 0;
                break;
            case VariableCalculation::AVERAGE:
                $value = ($value * ($this->calculatedVariables['REPORT_COUNT'] - 1) + $currentValue) / $this->calculatedVariables['REPORT_COUNT'];
                $value = 0;
                break;
            case VariableCalculation::DISTINCT_COUNT:
                break;
            case VariableCalculation::LOWEST:
                
                // foreach ($this->dbData as $rowData) {
                //     $lowest = $rowData->$out["target"];
                //     if ($rowData->$out["target"] < $lowest) {
                //         $lowest = $rowData->$out["target"];
                //     }
                //     $value = $lowest;
                // }
                break;
            case VariableCalculation::HIGHEST:
                // $out["ans"] = 0;
                // foreach ($this->arraysqltable as $table) {
                //     if ($rowData->$out["target"] > $out["ans"]) {
                //         $value = $rowData->$out["target"];
                //     }
                // }
                $value = 0;
                break;
            case VariableCalculation::COUNT:
                $value = $this->calculatedVariables[$name];
                $value++;
                
                break;
            case VariableCalculation::NOTHING:
                $value = $result;
                break;
        }

        if ($variable->resetType == ResetType::PAGE) {
            if ($this->pageChanged) {
                $value = $result;
            }
        }

        if ($variable->resetType == ResetType::GROUP) {
            
            if ($this->report->mainDataset->groups[$variable->resetGroup]->resetVariables == 'true') {
                
                $value = $result;
            }
        }
        
        $this->calculatedVariables[$name] = $value;
    }
}
