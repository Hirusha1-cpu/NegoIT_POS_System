<?php
require 'plugin/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->getProperties()
    ->setCreator($user)
	->setLastModifiedBy($user)
	->setTitle("$inf_company Inventory Unic List")
	->setSubject("$inf_company Inventory Unic List")
	->setDescription("$inf_company Inventory Unic List")
	->setKeywords("$inf_company")
	->setCategory("Inventory Unic List");

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1','Inventory Unic List');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Generated Date: '.$today);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Store: '.$item_store);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFill()->getStartColor()->setARGB('FF808080');

// Create a first sheet, representing sales data
$objPHPExcel->getActiveSheet()->getStyle('C1')->getNumberFormat()->setFormatCode(Style\NumberFormat::FORMAT_DATE_XLSX15);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A4', 'CATEGORY');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'ITEM');
$objPHPExcel->getActiveSheet()->setCellValue('C4', 'UNIC ID');
$objPHPExcel->getActiveSheet()->setCellValue('D4', 'SHIPMENT DATE');
$objPHPExcel->getActiveSheet()->setCellValue('E4', 'TRANSFER DATE');

$cat='';
$k=0;
for($j=0;$j<sizeof($item_sn);$j++){
	$cell1=$k+5;
	$objPHPExcel->getActiveSheet()->getStyle('C'.$cell1)->getNumberFormat()->setFormatCode(Style\NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $item_category[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $item_desc[$j]);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$cell1, $item_sn[$j],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell1, $item_shipment_date[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$cell1, $item_trans_date[$j]);
	if($item_inv[$j]=='trans')
	$objPHPExcel->getActiveSheet()->getStyle("A$cell1:E$cell1")->getFont()->getColor()->setARGB(Style\Color::COLOR_BLUE);
	if($item_inv[$j]=='bill')
	$objPHPExcel->getActiveSheet()->getStyle("A$cell1:E$cell1")->getFont()->getColor()->setARGB(Style\Color::COLOR_RED);
	$k++;
}
$k++;

$cell3=3+$k;
$cell4=6+$k;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setShrinkToFit(true);

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'borderStyle' => Style\Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$cell1=3+$k;

$objPHPExcel->getActiveSheet()->getStyle("A4:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B4:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("C4:C$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("D4:D$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("E4:E$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('Inventory Unic List');

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

// Set the active sheet index before saving
$objPHPExcel->setActiveSheetIndex(0);

// ============================ NEW PAGE ============================ //

// $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($objPHPExcel, 'Inventory Unic Item Cost');
// $spreadsheet->addSheet($myWorkSheet, 1);
$objPHPExcel->createSheet();
// Set properties for the second sheet
$objPHPExcel->getProperties()
    ->setCreator($user)
    ->setLastModifiedBy($user)
    ->setTitle("$inf_company Inventory Unic Item Cost")
    ->setSubject("$inf_company Inventory Unic Item Cost")
    ->setDescription("$inf_company Inventory Unic Item Cost")
    ->setKeywords("$inf_company")
    ->setCategory("Inventory Unic Item Cost");

$objPHPExcel->setActiveSheetIndex(1);
// Set header design for the second sheet
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Inventory Unic Item Cost');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Generated Date: ' . $today);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Store: ' . $item_store);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFill()->getStartColor()->setARGB('FF808080');

$objPHPExcel->getActiveSheet()->getStyle('C1')->getNumberFormat()->setFormatCode(Style\NumberFormat::FORMAT_DATE_XLSX15);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A4', 'CATEGORY');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'ITEM');
$objPHPExcel->getActiveSheet()->setCellValue('C4', 'QTY');
$objPHPExcel->getActiveSheet()->setCellValue('D4', 'COST');
$objPHPExcel->getActiveSheet()->setCellValue('E4', 'VALUE');

$k=0;
$total=0;
$cell2=0;
for($j=0;$j<sizeof($item_desc_1);$j++){
	$cell1=$k+5;
	$cell2=$k+5;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $item_category_1[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $item_desc_1[$j]);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$cell1, $item_qty_1[$j],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$cell1, $item_cost_1[$j],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$cell1, $items_values_1[$j],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$cell1)->getNumberFormat()
    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$cell1)->getNumberFormat()
    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$k++;
	$total += $items_values_1[$j];
}
$k++;
$cell2++;
if(sizeof($item_desc_1)== 0){
	$cell2=5;
}
// Total cell
$objPHPExcel->getActiveSheet()->getStyle('D'.$cell2)->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('D'.$cell2)->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('D'.$cell2)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$cell2)->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('D'.$cell2)->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('D'.$cell2)->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell2, 'Total');

// Total value showing cell
$objPHPExcel->getActiveSheet()->setCellValue('E'.$cell2, $total);
$objPHPExcel->getActiveSheet()->getStyle('E'.$cell2)->getNumberFormat()
->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$cell3=3+$k;
$cell4=6+$k;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setShrinkToFit(true);

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'borderStyle' => Style\Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$cell1=3+$k;

$objPHPExcel->getActiveSheet()->getStyle("A4:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B4:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("C4:C$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("D4:D$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("E4:E$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('Inventory Unic Item Cost');

// Set the active sheet index for the second sheet
$objPHPExcel->setActiveSheetIndex(0);

//Download the Excel file
$writer = new Xlsx($objPHPExcel);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode("Inventory_Unic_List".'.xlsx').'"');
$writer->save('php://output');
exit;
?>