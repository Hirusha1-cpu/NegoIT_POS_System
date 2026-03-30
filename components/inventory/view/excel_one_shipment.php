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
	->setTitle("Shipment : $shipment_no")
	->setSubject("$inf_company Shipment")
	->setDescription("$inf_company | Shipment")
	->setKeywords("$inf_company")
	->setCategory("Shipment");

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ITEM');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'UNIT PRICE');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'UNIQUE ID');

$k=0;
for($j=0;$j<sizeof($sh_itm_des);$j++){
	$cell1=$k+2;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $sh_itm_des[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $sh_uprice[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$cell1, $sh_unic_id[$j]);
	$k++;
}
$k++;

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);


$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'borderStyle' => Style\Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$cell1=$k;

$objPHPExcel->getActiveSheet()->getStyle("A1:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B1:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("C1:C$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('One Shipment');

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
$objPHPExcel->setActiveSheetIndex(0);

//Download the Excel file
$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode("One_Shipment".'.xlsx').'"');
$writer->save('php://output');
exit;
?>