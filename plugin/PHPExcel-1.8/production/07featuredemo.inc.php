<?php

error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($user)
							 ->setLastModifiedBy($user)
							 ->setTitle("$inf_company Inventory Unic List")
							 ->setSubject("$inf_company Inventory Unic List")
							 ->setDescription("$inf_company | Inventory Unic List")
							 ->setKeywords("$inf_company")
							 ->setCategory("Inventory Unic List");


$objPHPExcel->getActiveSheet()->setCellValue('A1','Inventory Unic List');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Generated Date: '.$today);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Store: '.$item_store);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFill()->getStartColor()->setARGB('FF808080');
// Create a first sheet, representing sales data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A4', 'CATEGORY');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'ITEM');
$objPHPExcel->getActiveSheet()->setCellValue('C4', 'UNIC ID');
$objPHPExcel->getActiveSheet()->setCellValue('D4', 'SHIPMENT DATE');
$objPHPExcel->getActiveSheet()->setCellValue('E4', 'TRANSFER DATE');

$cat='';
$k=0;
for($j=0;$j<sizeof($item_sn);$j++){
	$cell1=$k+5;
	$objPHPExcel->getActiveSheet()->getStyle('C'.$cell1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $item_category[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $item_desc[$j]);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$cell1, $item_sn[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell1, $item_shipment_date[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$cell1, $item_trans_date[$j]);
	if($item_inv[$j]=='trans')
	$objPHPExcel->getActiveSheet()->getStyle("A$cell1:E$cell1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
	if($item_inv[$j]=='bill')
	$objPHPExcel->getActiveSheet()->getStyle("A$cell1:E$cell1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
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
			'style' => PHPExcel_Style_Border::BORDER_THIN,
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
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('Inventory Unic List');
$sLloremIpsum ='';
//$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;
//$objPHPExcel->getActiveSheet()->getStyle('A3:A10000')->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle('B3:B10000')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->setActiveSheetIndex(0);