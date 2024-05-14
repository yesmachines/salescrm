<?php

namespace App\Services;

use Exception;
use Mpdf\Mpdf;

class CreateWordDocxService
{
  public function generatePdf(
    $quotation,
    $quotationItems,
    $quotationCharges,
    $quotationTerms
  ): void {

    $config = [
      'margin_footer' => 18,
      'format' => 'A4', // Set paper size to A4
    ];

    $mpdf = new \Mpdf\Mpdf($config);


    // Add header
    $this->getHeader($mpdf);


    $this->getFooter($mpdf);

    // Add content

    $this->addContent(
      $mpdf,
      $quotation,
      $quotationItems,
      $quotationCharges,
      $quotationTerms
    );

    // Add footer


    $fileName = $quotation->reference_no . '.pdf';
    if (request()->query('action') === 'preview') {
      $mpdf->Output($fileName, 'I');
    } else {
      $mpdf->Output($fileName, 'D');
    }
  }

  public function addContent(
    $mpdf,
    $quotation,
    $quotationItems,
    $quotationCharges,
    $quotationTerms
  ): void {

    $html = '<br><br><br><br><br><br>'; // This will create a vertical space of 20px
    $commonTableStyle = 'font-size: 10pt; font-family: Calibri; padding: 7px;background-color: #9DC33B; vertical-align: middle;text-align: left;border: 4px solid #ffffff;';
    $html .= '<table style="border-collapse: separate; border: 0; width: 100%;">
    <tr>
    <td style="vertical-align: top; border: 0; text-align: left;">
    <table style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr>';
    $html .= '<td style="' . $commonTableStyle . ' font-weight: bold;color: #ffffff;white-space: nowrap;">OUR OFFER #</td>';
    $html .= '<td style="' . $commonTableStyle . '">' . htmlspecialchars($quotation->reference_no) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td style="' . $commonTableStyle . ' font-weight: bold;color: #ffffff;white-space: nowrap;">DATE</td>';
    $html .= '<td style="' . $commonTableStyle . '">' . htmlspecialchars($quotation->submitted_date) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td style="' . $commonTableStyle . ' font-weight: bold;color: #ffffff;white-space: nowrap;">CLIENT</td>';
    $html .= '<td style="' . $commonTableStyle . '">' . htmlspecialchars(str_replace('&', ' ', $quotation->company->company)) . '</td>';
    $html .= '</tr>';

    $location = '';
    if ($quotation->company->region_id > 0) {
      $location .= ($quotation->company->region) ?  htmlspecialchars(str_replace('&', ' ', $quotation->company->region->state)) . ' '   : '';
    }
    if ($quotation->company->country_id > 0) {
      $location .= ($quotation->company->country) ? htmlspecialchars(str_replace('&', ' ', $quotation->company->country->name)) : '';
    }
    if ($location) {
      $html .= '<tr>';
      $html .= '<td style="' . $commonTableStyle . ' font-weight: bold;color: #ffffff;white-space: nowrap;">LOCATION</td>';
      $html .= '<td style="' . $commonTableStyle . '">' . $location . '</td>';
      $html .= '</tr>';
    }

    $html .= '<tr>';
    $html .= '<td style="' . $commonTableStyle . ' font-weight: bold;color: #ffffff;">CONTACT</td>';
    $html .= '<td style="' . $commonTableStyle . '">' . htmlspecialchars(str_replace('&', ' ', $quotation->customer->fullname)) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td style="' . $commonTableStyle . ' font-weight: bold; color: #ffffff;white-space: nowrap;">MOBILE</td>';
    $html .= '<td style="' . $commonTableStyle . '">' . htmlspecialchars($quotation->customer->phone) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td style="' . $commonTableStyle . ' font-weight: bold;color: #ffffff;white-space: nowrap;">EMAIL</td>';
    $html .= '<td style="' . $commonTableStyle . '">' . htmlspecialchars($quotation->customer->email) . '</td>';
    $html .= '</tr>';

    $html .= '</table>';

    $commonStyle = 'font-size: 10pt; font-family: Calibri; margin:0 0 0 0;line-height: 2.5;';

    $commonLinkStyle = 'color: #9dc33b; text-decoration: underline;'; // Add margin bottom
    $html .= '<br>';
    $html .= '<p style="' . $commonStyle . '">Dear Sir,</p>';
    $html .= '<br>';
    $html .= '<p style="' . $commonStyle . '">We thank you for your enquiry and interest in our products.Regard to your enquiry, we are pleased to submit our proposal
    model:</p>';
    $html .= '<br>';


    if ($quotation->supplier_id > 0) {
      // old supplier list
      $html .= '<ul style="' . $commonStyle . '"><li>' . htmlspecialchars($quotation->product_model) . '</li></ul>';
      $html .= '<br>';
      $html .= '<p style="' . $commonStyle . '">from our Principals ';
      $html .= '<b>' . isset($quotation->supplier) ? $quotation->supplier->brand : '';
      $html .= '</b>.</p>';
    } else {
      $supplier = [];
      // new quotation item lists
      if (!empty($quotationItems)) {

        foreach ($quotationItems as $key => $value) {
          $txt_tmp = '';
          $modelno = $value->product->modelno ? $value->product->modelno : ($value->product->part_number ? $value->product->part_number : '');
          if (!$modelno) {
            $modelno = $value->description;
          }
          $html .= '<ul style="' . $commonStyle . '"><li>' . htmlspecialchars($modelno) . '</li></ul>';

          $txt_tmp .= isset($value->supplier) ? $value->supplier->brand . ' ' : '';
          $txt_tmp .= isset($value->supplier->country) ? $value->supplier->country->name : '';

          if (!in_array($txt_tmp,  $supplier)) {
            $supplier[] = $txt_tmp;
          }
        }
        $html .= '<br>';
        $html .= '<p style="' . $commonStyle . '">from our Principals ';
        $html .= '<b>' . implode(", ", $supplier);
        $html .= '</b>.</p>';
      }
    }

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

    $html .= '<br>';
    $html .= '<p style="' . $commonStyle . '">YES machinery, operating over two verticals </p>';
    $html .= '<p style="' . $commonStyle . '">(Steel Machinery and Smart Industrial Solutions)</p>';
    $html .= '<p style="' . $commonStyle . '">represent and promote various reputed brands in this region.</p>';
    $html .= '<p style="' . $commonStyle . '">More details are on <a href="https://www.yesmachinery.ae" style="' . $commonLinkStyle . '">www.yesmachinery.ae</a></p>';
    $html .= '<br>';
    $html .= '<p style="' . $commonStyle . '">Kindly study and let us know your feedback.</p>';
    $html .= '<br>';
    $html .= '<p style="' . $commonStyle . ' margin-top: 20px;">Thanks and Best Regards,</p>';
    $html .= '<br>';
    $html .= '<p style="' . $commonStyle . ' text-transform: uppercase;"><b>' . $quotation->assigned->user->name . '</b></p>';
    $html .= '<p style="' . $commonStyle . '">' . $quotation->assigned->designation . ' - ' . $division . '</p>';
    $html .= '<p style="' . $commonStyle . '">' . $quotation->assigned->phone . '</p>';
    $html .= '<p style="' . $commonStyle . '"><a href="mailto:' . $quotation->assigned->user->email . '" target="_blank" style="' . $commonLinkStyle . '">' . $quotation->assigned->user->email . '</a></p>';
    // Adding images using WriteHTML
    $html .= '<br><img src="quotes/img/fb.jpeg" width="20">';
    $html .= '<img src="quotes/img/in.jpeg" width="20" >';
    $html .= '<img src="quotes/img/ln.jpeg" width="20">';
    $html .= '<img src="quotes/img/yu.jpeg" width="20">';
    $html .= '<br>'; // This will create a line break
    $html .= '<img src="quotes/img/qr-code.png"  width="70" style="margin-left:8px;margin-top:8px;">';

    $html .= '<td style="vertical-align: top; border: 0; text-align: right;">
    <img src="quotes/img/sidebanner.png" alt="Side Banner" style="width: 35%;">
    </td>
    </tr>
    </table>';

    // Add HTML content to mPDF
    $mpdf->WriteHTML($html);
    $html = '';
    $mpdf->AddPage();

    $titleStyle1 = 'font-size:12pt; font-family: Calibri;text-align: left; font-weight: bold; text-decoration: underline; color: #555555;margin-left:20px;';
    // $this->getHeader($mpdf);
    if ($quotationItems->isNotEmpty()) {
      $html = '<br><br><br><br><br>';
      $html .= '<p style="' . $titleStyle1 . '">PRODUCT ITEMS</p>';
      $html .= '
      <table style="border-collapse: collapse; width: 90%;">
      <tr>
      <td style="border: 1px solid #9DC33B; padding: 8px; font-weight: bold; background-color: #9DC33B; color: white; font-size: 10pt; font-family: Calibri;">Product Name</td>
      <td style="border: 1px solid #9DC33B; padding: 8px; font-weight: bold; background-color: #9DC33B; color: white; font-size: 10pt; font-family: Calibri;">Unit Price</td>
      <td style="border: 1px solid #9DC33B; padding: 8px; font-weight: bold; background-color: #9DC33B; color: white; font-size: 10pt; font-family: Calibri;">Qty</td>
      <td style="border: 1px solid #9DC33B; padding: 8px; font-weight: bold; background-color: #9DC33B; color: white; font-size: 10pt; font-family: Calibri;">Discount</td>
      <td style="border: 1px solid #9DC33B; padding: 8px; font-weight: bold; background-color: #9DC33B; color: white; font-size: 10pt; font-family: Calibri;">Total Amount</td>
      </tr>';

      foreach ($quotationItems as $key => $value) {
        $title = htmlspecialchars(str_replace('&', ' ', $value->description));
        $brand = htmlspecialchars(str_replace('&', ' ', $value->supplier->brand));
        $html .= '<tr>';
        $html .= '<td style="border: 1px solid #9DC33B; padding: 8px; font-size: 10pt; font-family: Calibri;">' . $brand . '<br>' . $title . '</td>';
        $html .= '<td style="border: 1px solid #9DC33B; padding: 8px; font-size: 10pt; font-family: Calibri;">' . htmlspecialchars(number_format($value['unit_price'], 2)) . ' ' . $quotation->preferred_currency . '</td>';
        $html .= '<td style="border: 1px solid #9DC33B; padding: 8px; font-size: 10pt; font-family: Calibri;">' . htmlspecialchars($value['quantity']) . '</td>';
        $html .= '<td style="border: 1px solid #9DC33B; padding: 8px; font-size: 10pt; font-family: Calibri;">' . htmlspecialchars($value['discount']) . '% </td>';
        $html .= '<td style="border: 1px solid #9DC33B; padding: 8px; font-size: 10pt; font-family: Calibri;">' . htmlspecialchars(number_format($value['total_after_discount'], 2)) . ' ' . $quotation->preferred_currency . '</td>';
        $html .= '</tr>';
      }
      $html .= '<tr>
      <td colspan="5" style="font-size: 10pt; font-family: Calibri; border: 1px solid #9DC33B; padding: 8px; text-align: left; font-weight: bold; background-color: #9DC33B; color: white;">Additional Charges</td>
      </tr>';
      foreach ($quotationCharges as $key => $getQuotioncharge) {
        $html .= '<tr>';
        $html .= '<td colspan="4" style="border: 1px solid #9DC33B; padding: 8px; text-align: right; font-size: 10pt; font-family: Calibri;">' . htmlspecialchars(str_replace("&", " ", $getQuotioncharge['title'])) . '</td>';
        $html .= '<td style="border: 1px solid #9DC33B; padding: 8px; font-size: 10pt; font-family: Calibri;">' . htmlspecialchars(number_format($getQuotioncharge['amount'], 2)) . ' ' . $quotation->preferred_currency . '</td>';
        $html .= '</tr>';
      }

      if ($quotation->is_vat == 1) {
        $html .= '
        <tr>
        <td colspan="4" style="font-size: 10pt; font-family: Calibri; border: 1px solid #9DC33B; padding: 8px; text-align: right;font-weight: bold;">Vat Amount (Include 5%)</td>
        <td style="border: 1px solid #9DC33B; padding: 8px;font-size: 10pt; font-family: Calibri;">' . htmlspecialchars(number_format($quotation->vat_amount, 2)) . ' ' . $quotation->preferred_currency . '</td>
        </tr>';
      } else {
        $html .= '
        <tr>
        <td colspan="4" style="border: 1px solid #9DC33B; padding: 8px; text-align: right;font-weight: bold;font-size: 10pt; font-family: Calibri;">Vat Amount</td>
        <td style="border: 1px solid #9DC33B; padding: 8px;font-size: 10pt; font-family: Calibri;">Exclusive</td>
        </tr>';
      }


      $html .= '
      <tr>
      <td colspan="4" style="font-size: 10pt; font-family: Calibri;border: 1px solid #9DC33B; padding: 8px; text-align: right;font-weight: bold;">Total Amount: </td>
      <td style="font-size: 10pt; font-family: Calibri;border: 1px solid #9DC33B; padding: 8px;">' . htmlspecialchars(number_format($quotation->total_amount, 2)) . ' ' . $quotation->preferred_currency . '</td>
      </tr>
      </table>';
    }

    $mpdf->WriteHTML($html);


    $mpdf->AddPage();
    // $this->getHeader($mpdf);

    $titleStyle = 'font-size:12pt; font-family: Calibri;text-align: left; font-weight: bold; text-decoration: underline; color: #555555;margin-left:20px;';
    $predefinedListItemStyle = 'font-family: Calibri; list-style-type: decimal; margin-bottom: 3mm; margin-top: 5px;font-size:10pt;';
    $html = '<br><br><br><br><br>';
    $html .= '<p style="' . $titleStyle . '">BUYER SCOPE</p>';
    $html .= '<ol>';
    $html .= '<li style="' . $predefinedListItemStyle . '">Site readiness</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">Any form of civil works, foundations (where needed)</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">Off-loading the machine and shifting and placing to the area of installation</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">All lifting equipment (eg. Forklifts, crane etc.) and related personnel needed for installation and commissioning at the buyer site</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">Approachable, accessible and safe area for installation</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">All the necessary electrical, gas, air, network infrastructure, Hydraulic oil and other utilities availability</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">Third party inspection or any other forms of certification where needed</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">SASO certification (where applicable)</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">Availability of client technicians for training and handing over within the agreed time of I and C</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">For delivery to a free zone, charges related to bill of entry, gate passes and any other documentation relevant and applicable to that free zone will be in Buyer’s scope</li>';
    $html .= '<li style="' . $predefinedListItemStyle . '">Primary power source and its wiring works</li>';
    $html .= '</ol>';
    $html .= '<br>';

    $mpdf->WriteHTML($html);
    $mpdf->AddPage();


    $tableStyle = 'border-color: #ffffff; border-width: 0; cellpadding: 0; cellspacing: 0;font-size: 10pt; font-family: Calibri; ';
    $rowStyle = 'font-family: Calibri; font-size: 10pt;';

    $html = '<br><br><br><br><br><br>';
    $html .= '<p style="margin-top:0px;' . $titleStyle . '">TERMS AND CONDITIONS</p>';
    $html .= '<table style="margin-left:18px;' . $tableStyle . '">';
    if ($quotationTerms->isNotEmpty()) {
      foreach ($quotationTerms as $index => $term) {
        $html .= '<tr>';
        $html .= '<td style="' . $rowStyle . ' white-space: nowrap; padding-right: 10px; vertical-align: top;">' . htmlspecialchars(str_replace('&', ' ', $term['title'])) . '</td>';
        $html .= '<td style="' . $rowStyle . ' padding-bottom: 5px; line-height: 1.5; vertical-align: top; text-align:justify;">' . nl2br(htmlspecialchars(str_replace('&', ' ', $term['description']))) . '</td>';
        $html .= '</tr>';
      }
    }

    $html .= '</table>';


    $emp_name = str_replace('&', ' ', $quotation->assigned->user->name);
    $emp_desig = $quotation->assigned->designation . ' - ' . $division;
    $emp_desig = str_replace('&', ' ', $emp_desig);

    $emp_phone = $quotation->assigned->phone;
    $emp_email = $quotation->assigned->user->email;
    //$html = '<br><br><br><br><br>';
    $html .= '<p style="margin-bottom: 5px; margin-top: 10px;font-style: italic; font-family: Calibri; font-size: 10pt;">';
    $html .= 'We hope this offer meets with your requirements and look forward to receive your valued order. ';
    $html .= 'If you need any further clarifications, please feel free to contact us.';
    $html .= '</p>';
    $html .= '<p style="margin-top: 30px;  font-family: Calibri; font-size: 10pt;">Thanks and Best Regards,</p>';
    $html .= '<table style="width: 100%; margin-top: 15px; margin-bottom: 0px;  font-family: Calibri; font-size:10pt;">';
    $html .= '<tr>';
    $html .= '<td style="width: 45%; text-align: left; font-weight: bold; vertical-align: top; font-size: 10pt; font-family: Calibri;">';
    $html .= '<span style="font-weight: bold;text-transform: uppercase;">' . $emp_name . '</span><br>';
    $html .= '<span style="font-weight: normal; ">' . $emp_desig . '</span><br>';
    $html .= '<span style="font-weight: normal;">' . $emp_phone . '</span><br>';
    $html .= '<span style="font-weight: normal; ">' . $emp_email . '</span><br>';
    $html .= '</td>';
    $html .= '<td style="width: 10%; "></td>';
    $html .= '<td style="width: 45%; text-align: right; vertical-align: top; font-size: 10pt; font-family: Calibri;">';
    $html .= '<b>BASANTH RAGHAVAN</b><br>';
    $html .= 'Sales Director<br>';
    $html .= '+971 50 899 3781<br>';
    $html .= 'basanth@yesmachinery.ae<br>';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $mpdf->WriteHTML($html);
  }

  public function getHeader($mpdf): void
  {
    $html = '<img src="quotes/img/header.png" width="100%" style="padding-top: -33px; margin-bottom: 10px;"/>';

    $mpdf->SetHTMLHeader($html);
  }
  public function getFooter($mpdf): void
  {
    $html = '<img src="quotes/img/footer-top.png" width="100%" height="2" style="margin-bottom: 0;margin-bottom:2px;"/>';
    $html .= '<table >';
    $html .= '<tr>';
    $html .= '<td style="width: 35%; font-size: 8pt; font-family: Calibri; text-align: left;"><b>YORK ENGINEERING SOLUTIONS FZC, </b><br />Office No.LV-27D, PO BOX 42167,<br> Hamriyah Free Zone Phase 2, Sharjah, UAE</td>';
    $html .= '<td style="width: 30.33%; font-size: 8pt; font-family: Calibri;text-align: center;vertical-align: top;">Page {PAGENO} of {nb}</td>';
    $html .= '<td style="width: 41.33%;font-size: 8pt; font-family: Calibri;text-align: right;">Tel: +971 65 264 382 | Fax: +971 65 264 384<br>Mail: sales@yesmachinery.ae</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $mpdf->SetHTMLFooter($html);
  }
}
