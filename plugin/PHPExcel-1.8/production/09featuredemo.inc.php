<?php

error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($user)
							 ->setLastModifiedBy($user)
							 ->setTitle("Purchase Order | Repair Parts")
							 ->setSubject("Repair Parts Reorder")
							 ->setDescription("$inf_company | Repair Parts Reorder")
							 ->setKeywords("$inf_company")
							 ->setCategory("Purchase Order");


//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ITEM');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Reorder QTY');

$cat='';
$k=0;
for($j=0;$j<sizeof($rp_name);$j++){
	$cell1=$k+2;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $rp_name[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $ri_reorder_qty[$j]);
	$k++;
}
$k++;

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$cell1=$k;

$objPHPExcel->getActiveSheet()->getStyle("A1:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B1:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('Purchase Order');
$sLloremIpsum ='';
//$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;
//$objPHPExcel->getActiveSheet()->getStyle('A3:A10000')->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle('B3:B10000')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->setActiveSheetIndex(0);