<?php
require 'plugin/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style;

$objPHPExcel = new Spreadsheet();
$activeWorksheet = $objPHPExcel->getActiveSheet();
#$activeWorksheet->setCellValue('A1', 'Hello World !');


//--------------------------------------------------------------------------------------------------//
if($extra) $end='E'; else  $end='C';

$objPHPExcel->getProperties()->setCreator($user)
							 ->setLastModifiedBy($user)
							 ->setTitle("$inf_company Purchase Order")
							 ->setSubject("$inf_company Purchase Order")
							 ->setDescription("$inf_company | Purchase Order : ".str_pad($po_no, 7, '0', STR_PAD_LEFT))
							 ->setKeywords("PO $inf_company")
							 ->setCategory("Purchase Order");


$objPHPExcel->getActiveSheet()->setCellValue('B1',$inf_company);
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Supplier: '.$po_sup);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Style\Color::COLOR_BLUE);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);

// Create a first sheet, representing sales data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Purchase Order');
$objPHPExcel->getActiveSheet()->setCellValue('C4', 'PO# '.str_pad($po_no, 7, "0", STR_PAD_LEFT));
$objPHPExcel->getActiveSheet()->setCellValue('C1', Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
$objPHPExcel->getActiveSheet()->getStyle('C1')->getNumberFormat()->setFormatCode(Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
#$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->getColor()->setARGB('ffffff');
$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'No');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Description');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'Quantity');
if($extra){
	$objPHPExcel->getActiveSheet()->setCellValue('D6', 'Store W/Price');
	$objPHPExcel->getActiveSheet()->setCellValue('E6', 'Drawer No');
}

$cat='';
$k=0;
for($j=0;$j<sizeof($po_item);$j++){
	$cell1=$k+7;
 	if($cat!=$po_category[$j]){
 		if($cat!=''){ $k++; $cell1=$k+7; }
 		$objPHPExcel->getActiveSheet()->setCellValue("B$cell1", $po_category[$j]); 
		$objPHPExcel->getActiveSheet()->getStyle("B$cell1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("B$cell1")->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle("A$cell1:$end$cell1")->getFill()->setFillType(Style\Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$cell1:$end$cell1")->getFill()->getStartColor()->setARGB('FF808080');
 		$k++; $cell1=$k+7;
 		$cat=$po_category[$j];
 	}
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $j+1);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $po_item[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$cell1, $po_qty[$j]);
	if($extra){
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell1, $po_wprice[$j]);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$cell1, $po_drawer[$j]);
	}
	$k++;
}
$k++;


$cell3=3+$k;
$cell4=6+$k;
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);	// Needs to be set to true in order to enable any worksheet protection!
$objPHPExcel->getActiveSheet()->protectCells("A$cell3:C$cell4", '0716154808');


// Set column widths
//echo date('H:i:s') , " Set column widths" , EOL;
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
if($extra){
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
}

// Set fonts
//echo date('H:i:s') , " Set fonts" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setUnderline(Style\Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->getColor()->setARGB(Style\Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setShrinkToFit(true);

// Set thin black border outline around column
//echo date('H:i:s') , " Set thin black border outline around column" , EOL;
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'borderStyle' => Style\Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$cell1=6+$k;

$objPHPExcel->getActiveSheet()->getStyle("A6:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B6:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("C6:C$cell1")->applyFromArray($styleThinBlackBorderOutline);
if($extra){
	$objPHPExcel->getActiveSheet()->getStyle("D6:D$cell1")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("E6:E$cell1")->applyFromArray($styleThinBlackBorderOutline);
}
$objPHPExcel->getActiveSheet()->getStyle("A6:".$end."6")->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle("A4:".$end."4")->getFill()->setFillType(Style\Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle("A4:".$end."4")->getFill()->getStartColor()->setARGB('FF808080');

// Set style for header row using alternative method
//echo date('H:i:s') , " Set style for header row using alternative method" , EOL;
$objPHPExcel->getActiveSheet()->getStyle("A6:".$end."6")->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => Style\Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => Style\Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => Style\Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		)
);

$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => Style\Alignment::HORIZONTAL_LEFT,
			),
			'borders' => array(
				'left'     => array(
 					'style' => Style\Border::BORDER_THIN
 				)
			)
		)
);

$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => Style\Alignment::HORIZONTAL_LEFT,
        )
    )
);

$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray(
    array(
        'borders' => array(
            'right'     => array(
                 'style' => Style\Border::BORDER_THIN
             )
        )
    )
);
if($extra){
$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray(
        array(
            'alignment' => array(
                'horizontal' => Style\Alignment::HORIZONTAL_LEFT,
            )
        )
);

$objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray(
        array(
            'borders' => array(
                'right'     => array(
                     'style' => Style\Border::BORDER_THIN
                 )
            )
        )
);
}

// Unprotect a cell
//echo date('H:i:s') , " Unprotect a cell" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Style\Protection::PROTECTION_UNPROTECTED);

// Add a hyperlink to the sheet
//echo date('H:i:s') , " Add a hyperlink to an external website" , EOL;
$cell1=10+$k;
$cell2=11+$k;
$objPHPExcel->getActiveSheet()->setCellValue("C$cell1", $inf_web);
$objPHPExcel->getActiveSheet()->getCell("C$cell1")->getHyperlink()->setUrl('http://'.$inf_web);
$objPHPExcel->getActiveSheet()->getCell("C$cell1")->getHyperlink()->setTooltip('Navigate to website');
$objPHPExcel->getActiveSheet()->getStyle("C$cell1")->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_RIGHT);

//echo date('H:i:s') , " Add a hyperlink to another cell on a different worksheet within the workbook" , EOL;
$objPHPExcel->getActiveSheet()->setCellValue("C$cell2", 'Terms and conditions');
$objPHPExcel->getActiveSheet()->getCell("C$cell2")->getHyperlink()->setUrl("sheet://'Terms and conditions'!A1");
$objPHPExcel->getActiveSheet()->getCell("C$cell2")->getHyperlink()->setTooltip('Review terms and conditions');
$objPHPExcel->getActiveSheet()->getStyle("C$cell2")->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_RIGHT);

// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
$objDrawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('./images/icon'.$systemid.'.png');
$objDrawing->setHeight(36);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
$objDrawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('./images/logo'.$systemid.'.png');
$objDrawing->setHeight(36);
$objDrawing->setCoordinates("A$cell1");
$objDrawing->setOffsetX(10);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// Play around with inserting and removing rows and columns
//echo date('H:i:s') , " Play around with inserting and removing rows and columns" , EOL;
$objPHPExcel->getActiveSheet()->insertNewRowBefore(6, 10);
$objPHPExcel->getActiveSheet()->removeRow(6, 10);
$objPHPExcel->getActiveSheet()->insertNewColumnBefore('E', 5);
$objPHPExcel->getActiveSheet()->removeColumn('E', 5);

// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
//echo date('H:i:s') , " Set header/footer" , EOL;
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BInvoice&RPrinted on &D');
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

// Set page orientation and size
//echo date('H:i:s') , " Set page orientation and size" , EOL;
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

// Rename first worksheet
//echo date('H:i:s') , " Rename first worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Purchase Order');

// Create a new worksheet, after the default sheet
//echo date('H:i:s') , " Create a second Worksheet object" , EOL;
$objPHPExcel->createSheet();

// Llorem ipsum...
$sLloremIpsum ='';
//$sLloremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus eget ante. Sed cursus nunc semper tortor. Aliquam luctus purus non elit. Fusce vel elit commodo sapien dignissim dignissim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur accumsan magna sed massa. Nullam bibendum quam ac ipsum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin augue. Praesent malesuada justo sed orci. Pellentesque lacus ligula, sodales quis, ultricies a, ultricies vitae, elit. Sed luctus consectetuer dolor. Vivamus vel sem ut nisi sodales accumsan. Nunc et felis. Suspendisse semper viverra odio. Morbi at odio. Integer a orci a purus venenatis molestie. Nam mattis. Praesent rhoncus, nisi vel mattis auctor, neque nisi faucibus sem, non dapibus elit pede ac nisl. Cras turpis.';

// Add some data to the second sheet, resembling some different data types
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Terms and conditions');
$objPHPExcel->getActiveSheet()->setCellValue('A3', $sLloremIpsum);
$objPHPExcel->getActiveSheet()->setCellValue('A4', $sLloremIpsum);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $sLloremIpsum);
$objPHPExcel->getActiveSheet()->setCellValue('A6', $sLloremIpsum);

// Set the worksheet tab color
//echo date('H:i:s') , " Set the worksheet tab color" , EOL;
$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;

// Set alignments
//echo date('H:i:s') , " Set alignments" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getAlignment()->setWrapText(true);

// Set column widths
//echo date('H:i:s') , " Set column widths" , EOL;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);

// Set fonts
//echo date('H:i:s') , " Set fonts" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setUnderline(Style\Font::UNDERLINE_SINGLE);

$objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->setSize(8);

// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
$objDrawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$objDrawing->setName('Terms and conditions');
$objDrawing->setDescription('Terms and conditions');
$objDrawing->setPath('./images/termsconditions.jpg');
$objDrawing->setCoordinates('B14');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// Set page orientation and size
//echo date('H:i:s') , " Set page orientation and size" , EOL;
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

// Rename second worksheet
//echo date('H:i:s') , " Rename second worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Terms and conditions');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);







//Download the Excel file
$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode(str_pad($po_no, 7, "0", STR_PAD_LEFT).'.xlsx').'"');
#header('Content-Disposition: attachment;filename="PO-'.str_pad($po_no, 7, "0", STR_PAD_LEFT).'.xlsx"');
$writer->save('php://output');

?>