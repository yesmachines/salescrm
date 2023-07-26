<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\Shared\Converter;

class CreateWordDocxService
{
    public function generateWordDocx($quotation): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Setting various styles to be used

        $this->setPageStyle($phpWord);

        // Generating letter for each branch
        $section = $phpWord->addSection(array('marginLeft' => 700, 'marginRight' => 700, 'headerHeight' => 0, 'footerHeight' => 0));
        //$section = $phpWord->addSection();

        /************* Add header ******************/
        $this->getHeader($section);

        /************* Add footer ******************/
        $this->getFooter($section);

        /********************** RIGHT BANNER - column 2 ********************** */
        // $this->pageRightImage($section);

        $maintable = $section->addTable([
            'borderSize'  => 0,
            'cellSpacing' => 6,
            'borderColor' => 'ffffff',
            'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::START
        ]);
        $parent_row1 = $maintable->addRow();
        $parent_row1_cell1 = $maintable->addCell(6800, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff', 'align' => 'left']);
        $parent_row1_cell2 = $maintable->addCell(1500, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff', 'align' => 'right']);


        $parent_row1_cell2->addImage(
            'quotes/img/sidebanner.png',
            array(
                'width'         => 180,
                'wrappingStyle' => 'behind',
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
                'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
            )
        );

        // $child_table_1 = $parent_row1_cell2->addTable('style_child_table');
        // $child_table_1->addRow(200);
        // $child_table_1->addCell(1200, $child_table_cell_styles)->addText("Col 1");
        // $child_table_1->addCell(1200, $child_table_cell_styles)->addText("Col 2");

        /******************* LEFT - column 1  **************************/

        /************* TABLE - OFFER ******************/

        $fancyTableStyleName = 'Fancy Table';
        $fancyTableStyle = [
            'cellMargin' => 40,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
            'cellSpacing' => 20
        ];

        $phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle);

        $fancyTableCellStyle = ['valign' => 'bottom', 'bgColor' => '9DC33B', 'borderSize' => 4, 'borderColor' => 'ffffff'];
        $fancyTableCellBtlrStyle = ['valign' => 'bottom', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR];
        $fancyTableFontStyle = [
            'bold' => true,
            'valign' => 'bottom',
            'color' => '#ffffff',
            'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR
        ];

        $centertxt = ['align' => 'left', 'valign' => 'bottom'];

        // $table = $section->addTable($fancyTableStyleName);

        $table = $parent_row1_cell1->addTable($fancyTableStyleName);

        $company = str_replace('&', ' ', $quotation->company->company);
        $contactperson =  str_replace('&', ' ', $quotation->customer->fullname);

        $table->addRow();
        $table->addCell(1750, $fancyTableCellStyle)->addText("OUR OFFER #", $fancyTableFontStyle, $centertxt);
        $table->addCell(4800, $fancyTableCellStyle)->addText($quotation->reference_no, $fancyTableCellBtlrStyle);

        $table->addRow();
        $table->addCell(1750, $fancyTableCellStyle)->addText("DATE", $fancyTableFontStyle, $centertxt);
        $table->addCell(4800, $fancyTableCellStyle)->addText($quotation->submitted_date, $fancyTableCellBtlrStyle);

        $table->addRow();
        $table->addCell(1750, $fancyTableCellStyle)->addText("CLIENT", $fancyTableFontStyle, $centertxt);
        $table->addCell(4800, $fancyTableCellStyle)->addText($company, $fancyTableCellBtlrStyle);

        $table->addRow();
        $table->addCell(1750, $fancyTableCellStyle)->addText("CONTACT", $fancyTableFontStyle, $centertxt);
        $table->addCell(4800, $fancyTableCellStyle)->addText($contactperson, $fancyTableCellBtlrStyle);

        $table->addRow();
        $table->addCell(1750, $fancyTableCellStyle)->addText("MOBILE", $fancyTableFontStyle, $centertxt);
        $table->addCell(4800, $fancyTableCellStyle)->addText($quotation->customer->phone, $fancyTableCellBtlrStyle);

        $table->addRow();
        $table->addCell(1750, $fancyTableCellStyle)->addText("EMAIL", $fancyTableFontStyle, $centertxt);
        $table->addCell(4800, $fancyTableCellStyle)->addText($quotation->customer->email, $fancyTableCellBtlrStyle);

        $product = ($quotation->category_id) ? $quotation->category->name : "";
        $product = str_replace('&', ' and ', $product);

        $supplier = ($quotation->supplier_id) ? $quotation->supplier->brand : "";
        $supplier = str_replace('&', ' and ', $supplier);

        $origin = ($quotation->supplier_id) ? $quotation->supplier->country->name : "";
        $origin = str_replace('&', ' and ', $origin);

        switch ($quotation->assigned->division) {
            case 'sd':
                $division = 'Steel Division';
                break;
            case 'is':
                $division = 'Industrial Solution';
                break;
            case 'ct':
                $division = 'Cleaning Technology';
                break;
            case 'serv':
                $division = 'Maintenance and Service';
                break;
        }

        $emp_name = str_replace('&', ' ', $quotation->assigned->user->name);
        $emp_desig = $quotation->assigned->designation . ' - ' . $division;
        $emp_desig = str_replace('&', ' ', $emp_desig);

        $emp_phone = $quotation->assigned->phone;
        $emp_email = $quotation->assigned->user->email;

        /************* PARAGRAPH  ******************/
        // $section->addTextBreak(1);

        $html = '<p style="font-size: 12pt; font-family: Calibri; margin-top: 10pt;">Dear Sir,</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;">We thank you for your enquiry and interest in our products.</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;">Regard to your enquiry, we are pleased to submit our proposal</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;">model:</p>';
        $html .= '<ul style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;"><li></li></ul>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin-top: 8pt; margin-bottom: 8pt;">from our Principals <b>' . $supplier . ', ' . $origin . '</b>.</p>';

        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;">YES machinery, operating over two verticals </p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;">(Steel Machinery and Smart Industrial Machinery Solutions)</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;">represent and promote various reputed brands in this region.</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0 0 0 0;">More details are on <a href="https://www.yesmachinery.ae" style="color: #9dc33b;text-decoration:underline;">www.yesmachinery.ae</a></p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri;margin-top: 10pt;">Kindly study and let us know your feedback.</p>';

        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin-top: 5pt; margin-bottom: 8pt;">Thanks and Best Regards,</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0;text-transform:uppercase;"><b>' . $emp_name . '</b></p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0; text-transform:capitalize;">' . $emp_desig . '</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin:0;">' . $emp_phone . '</p>';
        $html .= '<p style="font-size: 12pt; font-family: Calibri; margin-bottom: 10pt;"><a href="mailto:' . $emp_email . '" target="_blank" style="text-decoration:underline;">' . $emp_email . '</a></p>';

        \PhpOffice\PhpWord\Shared\Html::addHtml($parent_row1_cell1, $html, false, false);

        $textrun = $parent_row1_cell1->addTextRun('p2Style');
        $textrun->addImage('quotes/img/fb.jpeg',  [
            'width' => 16
        ]);
        $textrun->addImage('quotes/img/in.jpeg',  [
            'width' => 16,
        ]);
        $textrun->addImage('quotes/img/ln.jpeg',  [
            'width' => 16,
        ]);
        $textrun->addImage('quotes/img/yu.jpeg',  [
            'width' => 16,
        ]);

        $parent_row1_cell1->addImage('quotes/img/qr-code.png',  [
            'width' => 50
        ]);

        $section->addTextBreak(0);

        /*************** BREAK PAGE **************************/
        $section->addPageBreak();

        $section->addPageBreak();

        /*************** BREAK PAGE - LAST PAGE **************************/
        // Add Heading elements
        $section->addTitle('BUYER SCOPE', 0);
        $section->addTextBreak(0);

        // list style
        $predefinedMultilevelStyle = ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER_NESTED, 'spaceAfter' => 0, 'spaceBefore' => 0];
        $section->addListItem('Site readiness', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('Any form of civil works, foundations (where needed)', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('Off-loading the machine and shifting and placing to the area of installation', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('All lifting equipment (eg. Forklifts, crane etc.) and related personnel needed for installation and commissioning at the buyer site', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('Approachable, accessible and safe area for installation', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('All the necessary electrical, gas, air, network infrastructure, Hydraulic oil and other utilities availability', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('Third party inspection or any other forms of certification where needed', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('SASO certification (where applicable)', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('Availability of client technicians for training and handing over within the agreed time of I and C', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('For delivery to a free zone, charges related to bill of entry, gate passes and any other documentation relevant and applicable to that free zone will be in Buyer’s scope', 0, null, $predefinedMultilevelStyle);
        $section->addListItem('Primary power source and its wiring works', 0, null, $predefinedMultilevelStyle);
        $section->addTextBreak(1);

        // Add Heading elements
        $section->addTitle('TERMS AND CONDITIONS', 0);
        $section->addTextBreak(0);

        $tableStyle = array(
            'borderColor' => 'ffffff',
            'borderSize'  => 0,
            'cellMargin'  => 0,
            'cellSpacing'  => 0
        );

        $table = $section->addTable($tableStyle);

        $table->addRow();
        $table->addCell(2250)->addText("Price Basis");
        $table->addCell(6500)->addText("Quoted in ");

        $table->addRow();
        $table->addCell(2250)->addText("Payment Term");
        $table->addCell(6500)->addText("100% advance along with Purchase order.");

        $table->addRow();
        $table->addCell(2250)->addText("Country of Origin");
        $table->addCell(6500)->addText($origin);

        $table->addRow();
        $table->addCell(2250)->addText("Delivery");
        $table->addCell(6500)->addText("15 to 20 working weeks from date of receipt of official order/advanced 
        payment/receipt letter of credit. + sea freight time frame ");

        $table->addRow();
        $table->addCell(2250)->addText("Installing and Training");
        $table->addCell(6500)->addText("Installing by YES MACHINERY Engineers (Charges to be defined)");

        $table->addRow();
        $table->addCell(2250)->addText("Warranty Period");
        $table->addCell(6500)->addText("Warranty shall cover component replacement under normal operation for a
        period of 24 months from date of delivery. However, warranty is not valid in the damage/breakdown caused 
        due to the operator negligence.");

        $table->addRow();
        $table->addCell(2250)->addText("Offer Validity");
        $table->addCell(6500)->addText("30 days");

        // $textrun = $section->addTextRun();
        // $section->addTextBreak(0);

        $textrun = $section->addTextRun('p2Style');
        $textrun->addText(
            "We hope this offer meets with your requirements and look forward to receive your valued order. If you need any further clarifications, please feel free to contact us ",
            'f3Style',
            array('spaceAfter' => 5, 'spaceBefore' => 10)
        );
        // $textrun = $section->addTextRun();
        // $section->addTextBreak(0);

        $textrun = $section->addTextRun();
        $textrun->addText(
            "Thanks and Best Regards,",
            'f1Style',
            'p2Style'
        );
        /*****************************/

        $table = $section->addTable();
        $cellHEnd = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'marginTop' => 0, 'marginBottom' => 0];
        $cellHead = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'marginTop' => 0, 'marginBottom' => 0, 'bold' => true];

        $table->addRow();
        $cell =  $table->addCell(4500);
        $textrun = $cell->addTextRun();
        $textrun->addText($emp_name . "<w:br/>", $cellHead);
        $textrun->addText(
            $emp_desig . "<w:br/>" . $emp_phone . "<w:br/>" . $emp_email,
            $cellHEnd
        );
        $table->addCell(2000);

        $cell2 =  $table->addCell(4500);
        $textrun2 = $cell2->addTextRun();
        $textrun2->addText(
            "BASANTH RAGHAVAN<w:br/>",
            $cellHead,
            array('align' => 'right')
        );
        $textrun2->addText(
            "Sales Director<w:br/>+971 50 899 3781<w:br/>basanth@yesmachinery.ae",
            $cellHEnd,
            array('align' => 'right')
        );

        // (D) OR FORCE DOWNLOAD
        try {
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment;filename=\"" . $quotation->reference_no . ".docx\"");
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
            $objWriter->save("php://output");
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function setPageStyle($phpWord): void
    {

        $phpWord->addParagraphStyle('p1Style', array('align' => 'both', 'spaceAfter' => 0, 'spaceBefore' => 0));
        $phpWord->addParagraphStyle('p2Style', array('align' => 'both'));
        $phpWord->addParagraphStyle('p3Style', array('align' => 'right', 'spaceAfter' => 0, 'spaceBefore' => 0));
        $phpWord->addFontStyle('f1Style', array('name' => 'Calibri', 'size' => 12));
        $phpWord->addFontStyle('f2Style', array('name' => 'Calibri', 'bold' => true, 'size' => 12));
        $phpWord->addFontStyle('f3Style', array('name' => 'Calibri', 'size' => 12, 'italic' => true));
        // Define styles       
        $phpWord->addTitleStyle(null, ['size' => 12, 'bold' => true, 'underline' =>  \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE, 'color' => '555555', 'spaceAfter' => 5]);
    }

    public function pageRightImage($section): void
    {
        $section->addImage('quotes/img/sidebanner.png',  [
            'width' => 180,
            'wrappingStyle' => 'behind',
            //'positioning' => 'relative',
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
            'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
            'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
            'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,

        ]);
    }
    public function getHeader($section): void
    {
        $header = $section->addHeader();
        $header->addImage('quotes/img/header.jpg',  [
            'width' => 530,
            'marginBottom'   => 0,
            'wrappingStyle' => 'square',
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ]);
    }

    public function getFooter($section): void
    {
        $footer = $section->addFooter();
        $footer->addImage('quotes/img/footer-top.png',  [
            'width' => 530,
            'height'   => 2,
            'wrappingStyle' => 'square',
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ]);
        $table = $footer->addTable();
        $table->addRow();

        $cell = $table->addCell(4500);
        $textrun = $cell->addTextRun();
        $textrun->addText("YORK ENGINEERING SOLUTIONS FZC, <w:br/>", ['bold' => true, 'size' => 9]);
        $textrun->addText(
            'Office No.LV-27D, PO BOX 42167,<w:br/>Hamriyah Free Zone Phase 2, Sharjah, UAE',
            array('size' => 9),
            array('align' => 'left')
        );

        $table->addCell(2000)->addPreserveText(
            'Page {PAGE} of {SECTIONPAGES}',
            array('size' => 9, 'bold' => true),
            array('align' => 'center')
        );

        $cell2 = $table->addCell(4750);
        $cell2->addText(
            'Tel: +971 65 264 382 | Fax: +971 65 264 384,<w:br/>Mail: sales@yesmachinery.ae',
            array('size' => 9, 'align' => 'right'),
            array('align' => 'right')
        );
    }
}
