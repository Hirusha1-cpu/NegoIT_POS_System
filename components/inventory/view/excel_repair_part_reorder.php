<?php
require 'plugin/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->getProperties()
    ->setCreator($user)
	->setLastModifiedBy($user)
	->setTitle("Purchase Order | Repair Parts")
	->setSubject("Repair Parts Reorder")
	->setDescription("$inf_company | Repair Parts Reorder")
	->setKeywords("$inf_company")
	->setCategory("Purchase Order");

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ITEM');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Reorder QTY');

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
			'borderStyle' => Style\Border::BORDER_THIN,
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
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('Purchase Order');

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->createSheet();

//Download the Excel file
$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode("Repair_Parts_PO".'.xlsx').'"');
$writer->save('php://output');
exit;
?>