<?php

error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($user)
							 ->setLastModifiedBy($user)
							 ->setTitle("$inf_company Sales Report")
							 ->setSubject("$inf_company Sales Report")
							 ->setDescription("$inf_company | Sales Report")
							 ->setKeywords("$inf_company")
							 ->setCategory("Sales Report");


$objPHPExcel->getActiveSheet()->setCellValue('A1','Sales Report');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->setCellValue('B1', $excel_title);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FF808080');
// Create a first sheet, representing sales data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A3', 'CATEGORY');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'STORE');
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'SALESMAN');
$objPHPExcel->getActiveSheet()->setCellValue('D3', 'CUSTOMER');
$objPHPExcel->getActiveSheet()->setCellValue('E3', 'DATE');
$objPHPExcel->getActiveSheet()->setCellValue('F3', 'ITEM');
$objPHPExcel->getActiveSheet()->setCellValue('G3', 'UNIC ID');
$objPHPExcel->getActiveSheet()->setCellValue('H3', 'SOLD PRICE');

$cat='';
$k=0;
for($j=0;$j<sizeof($item_sn);$j++){
	$multi_arr=array();
	$multi_arr=explode(',',$item_sn[$j]);
	for($n=0;$n<sizeof($multi_arr);$n++){
		$cell1=$k+4;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $item_category[$j]);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $item_store[$j]);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cell1, $item_salesman[$j]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell1, $item_cust[$j]);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$cell1, $item_bm_date[$j]);	
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$cell1, $item_desc[$j]);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$cell1, $multi_arr[$n], PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$cell1, $item_sold_price[$j]);
		$k++;
	}
}
$k++;

$cell3=3+$k;
$cell4=6+$k;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

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

$objPHPExcel->getActiveSheet()->getStyle("A3:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B3:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("C3:C$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("D3:D$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("E3:E$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("F3:F$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("G3:G$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("H3:H$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("H3:H$cell1")->getNumberFormat()->setFormatCode('#,##0.00');
 
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->getStartColor()->setARGB('001F4E78');
$objPHPExcel->getActiveSheet()->setTitle('Sales Report');
$sLloremIpsum ='';
//$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;
//$objPHPExcel->getActiveSheet()->getStyle('A3:A10000')->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle('B3:B10000')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->setActiveSheetIndex(0);