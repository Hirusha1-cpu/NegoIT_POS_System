<?php
require 'plugin/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style;

$objPHPExcel = new Spreadsheet();
$activeWorksheet = $objPHPExcel->getActiveSheet();

$objPHPExcel->getProperties()
    ->setCreator($user)
	->setLastModifiedBy($user)
	->setTitle("$inf_company Profit Report")
	->setSubject("$inf_company Profit Report")
	->setDescription("$inf_company Profit Report")
	->setKeywords("Profit Report $inf_company")
	->setCategory("Profit Report");

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Profit Report');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('B1', $excel_title);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FF808080');

$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('J3')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);


$objPHPExcel->getActiveSheet()->setCellValue('A3', '#');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'INVOICE NO');
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'DATE');
$objPHPExcel->getActiveSheet()->setCellValue('D3', 'CUSTOMER');
$objPHPExcel->getActiveSheet()->setCellValue('E3', 'TYPE');
$objPHPExcel->getActiveSheet()->setCellValue('F3', 'INVOICE PRICE');
$objPHPExcel->getActiveSheet()->setCellValue('G3', 'INVOICE COST');
$objPHPExcel->getActiveSheet()->setCellValue('H3', 'INVOICE PROFIT');
$objPHPExcel->getActiveSheet()->setCellValue('I3', 'ADVANCE PAID AMOUNT');
$objPHPExcel->getActiveSheet()->setCellValue('J3', 'TOTAL COLLECTION');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(120, 'pt');
$objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setShrinkToFit(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode('#,##0.00');

// Data populate
$k=0;
try {
    for($j=0;$j<sizeof($invoice_no);$j++){
        $cell1=$k+4;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, ($j+1));
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $invoice_no[$j]);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$cell1, $date[$j]);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$cell1, $cust[$j]);
        if($invoice_type[$j] != '') $objPHPExcel->getActiveSheet()->setCellValue('E'.$cell1, "HP");
        else $objPHPExcel->getActiveSheet()->setCellValue('E'.$cell1, "CASH");
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$cell1, $invoice_total[$j]);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$cell1, $invoice_cost[$j]);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$cell1, $invoice_profit[$j]);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$cell1, $advance_payment[$j]);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$cell1, $payments[$j]);
        $k++;
    }
}catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
    exit;
}

$styleThinBlackBorderOutline = [
    'borders' => [
        'outline' => [
            'borderStyle' => Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];
$cell1=3+$k;

$objPHPExcel->getActiveSheet()->getStyle("A3:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B3:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("C3:C$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("D3:D$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("E3:E$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("F3:F$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("G3:G$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("H3:H$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("I3:I$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("J3:J$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("H3:J$cell1")->getNumberFormat()->setFormatCode('#,##0.00');


$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('Profit Report');

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

$objPHPExcel->createSheet();

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//Download the Excel file
$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode("Profit Report".'.xlsx').'"');
$writer->save('php://output');
exit;
?>