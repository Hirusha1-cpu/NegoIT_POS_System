<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator($user)
							 ->setLastModifiedBy($user)
							 ->setTitle("$inf_company Purchase Order")
							 ->setSubject("$inf_company Purchase Order")
							 ->setDescription("$inf_company | Purchase Order : ".str_pad($po_no, 7, '0', STR_PAD_LEFT))
							 ->setKeywords("PO $inf_company")
							 ->setCategory("Purchase Order");


$objPHPExcel->getActiveSheet()->setCellValue('A1',strtoupper($inf_company));
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Supplier: '.$po_sup);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('BankGothic Lt BT');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
// Create a first sheet, representing sales data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'PURCHASE ORDER');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'DATE');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'PO No.');
$objPHPExcel->getActiveSheet()->setCellValue('I2', str_pad($po_no, 7, "0", STR_PAD_LEFT));
$objPHPExcel->getActiveSheet()->setCellValue('E2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
$objPHPExcel->getActiveSheet()->getStyle('E2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('H2:J2')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

$objPHPExcel->getActiveSheet()->setCellValue('A5', 'No');
$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Item Code');
$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Description');
$objPHPExcel->getActiveSheet()->setCellValue('D5', 'Qty.');
$objPHPExcel->getActiveSheet()->setCellValue('E5', 'RMB');
$objPHPExcel->getActiveSheet()->setCellValue('F5', 'Total (RMB)');
$objPHPExcel->getActiveSheet()->setCellValue('G5', 'Transport (RMB)');
$objPHPExcel->getActiveSheet()->setCellValue('H5', 'Other (RMB)');
$objPHPExcel->getActiveSheet()->setCellValue('I5', 'Total Cost (RMB)');
$objPHPExcel->getActiveSheet()->setCellValue('J5', 'APC (RMB)');

$cat='';
$k=0;
for($j=0;$j<sizeof($po_item);$j++){
	$cell1=$k+6;
 	if($cat!=$po_category[$j]){
 		if($cat!=''){ $k++; $cell1=$k+6; }
 		$objPHPExcel->getActiveSheet()->setCellValue("B$cell1", $po_category[$j]); 
		$objPHPExcel->getActiveSheet()->getStyle("B$cell1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("B$cell1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle("A$cell1:J$cell1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$cell1:J$cell1")->getFill()->getStartColor()->setARGB('FF808080');
 		$k++; $cell1=$k+6;
 		$cat=$po_category[$j];
 	}
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell1, $j+1);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell1, $po_item_code[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$cell1, $po_item[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell1, $po_qty[$j]);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$cell1, "=SUM(D$cell1*E$cell1)");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$cell1, "=SUM(F$cell1:H$cell1)");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$cell1, "=SUM(I$cell1/D$cell1)");
	$k++;
}
$k++;

$cell3=3+$k;
$cell4=5+$k;
$cell5=6+$k;
$cell6=7+$k;

$objPHPExcel->getActiveSheet()->setCellValue('F'.$cell5, 'TOTAL AMOUNT (RMB)');
$objPHPExcel->getActiveSheet()->setCellValue('I'.$cell5, "=SUM(I7:I$cell3)");
$objPHPExcel->getActiveSheet()->getStyle("F$cell5:I$cell5")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("F$cell5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("I$cell5")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle("I$cell5")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle("I$cell5")->getFill()->getStartColor()->setARGB('FF808080');
$objPHPExcel->getActiveSheet()->getStyle("I$cell5")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat:: FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->setCellValue("A$cell6", 'APPROVED BY -');
$objPHPExcel->getActiveSheet()->getStyle("A$cell6")->getFont()->setBold(true);

// Merge cells
//echo date('H:i:s') , " Merge cells" , EOL;
$objPHPExcel->getActiveSheet()->mergeCells("A1:J1");
$objPHPExcel->getActiveSheet()->mergeCells("F$cell5:H$cell5");
$objPHPExcel->getActiveSheet()->mergeCells("A$cell6:J$cell6");
$objPHPExcel->getActiveSheet()->mergeCells("E2:F2");
$objPHPExcel->getActiveSheet()->getStyle("E2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Add comment
//echo date('H:i:s') , " Add comments" , EOL;


// Add rich-text string
//echo date('H:i:s') , " Add rich-text string" , EOL;
/*
$objRichText = new PHPExcel_RichText();
$objRichText->createText('This invoice is ');

$objPayable = $objRichText->createTextRun('payable within thirty days after the end of the month');
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ) );

$objRichText->createText(', unless specified otherwise on the invoice.');
$cell1=9+$k;
$cell2=13+$k;

$objPHPExcel->getActiveSheet()->getCell("A$cell1")->setValue($objRichText);
*/


// Protect cells
//echo date('H:i:s') , " Protect cells" , EOL;
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);	// Needs to be set to true in order to enable any worksheet protection!
//$objPHPExcel->getActiveSheet()->protectCells("A6:D$cell4", '0716154808');


// Set column widths
//echo date('H:i:s') , " Set column widths" , EOL;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);

// Set fonts
//echo date('H:i:s') , " Set fonts" , EOL;

$objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);


// Set alignments
//echo date('H:i:s') , " Set alignments" , EOL;

//$objPHPExcel->getActiveSheet()->getStyle("A$cell1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
//$objPHPExcel->getActiveSheet()->getStyle("A$cell1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setShrinkToFit(true);

// Set thin black border outline around column
//echo date('H:i:s') , " Set thin black border outline around column" , EOL;
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$cell1=5+$k;

$objPHPExcel->getActiveSheet()->getStyle("A5:A$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("B5:B$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("C5:C$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("D5:D$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("E5:E$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("F5:F$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("G5:G$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("H5:H$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("I5:I$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle("J5:J$cell1")->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A5:J5')->applyFromArray($styleThinBlackBorderOutline);



// Set fills
//echo date('H:i:s') , " Set fills" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FF505050');

// Set style for header row using alternative method
//echo date('H:i:s') , " Set style for header row using alternative method" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('A5:J5')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
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
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
			'borders' => array(
				'left'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);

$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		)
);

$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray(
		array(
			'borders' => array(
				'right'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);
$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray(
		array(
			'borders' => array(
				'right'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);

// Unprotect a cell
//echo date('H:i:s') , " Unprotect a cell" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$objPHPExcel->getActiveSheet()->getStyle('E7:E'.$cell4)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$objPHPExcel->getActiveSheet()->getStyle('G7:H'.$cell4)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$objPHPExcel->getActiveSheet()->getStyle('E7:J'.$cell4)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat:: FORMAT_NUMBER_COMMA_SEPARATED1);

// Add a hyperlink to the sheet
//echo date('H:i:s') , " Add a hyperlink to an external website" , EOL;
$cell1=10+$k;
$cell2=11+$k;




// Play around with inserting and removing rows and columns
//echo date('H:i:s') , " Play around with inserting and removing rows and columns" , EOL;
/*
$objPHPExcel->getActiveSheet()->insertNewRowBefore(6, 10);
$objPHPExcel->getActiveSheet()->removeRow(6, 10);
$objPHPExcel->getActiveSheet()->insertNewColumnBefore('E', 5);
$objPHPExcel->getActiveSheet()->removeColumn('E', 5);
*/
// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
//echo date('H:i:s') , " Set header/footer" , EOL;
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BPurchase Order&RPrinted on &D');
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

// Set page orientation and size
//echo date('H:i:s') , " Set page orientation and size" , EOL;
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Rename first worksheet
//echo date('H:i:s') , " Rename first worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Purchase Order');

// Set page orientation and size
//echo date('H:i:s') , " Set page orientation and size" , EOL;
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Create a new worksheet, after the default sheet
//echo date('H:i:s') , " Create a second Worksheet object" , EOL;
/*
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
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

$objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->setSize(8);

// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
/*
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Terms and conditions');
$objDrawing->setDescription('Terms and conditions');
$objDrawing->setPath('./images/termsconditions.jpg');
$objDrawing->setCoordinates('B14');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


// Rename second worksheet
//echo date('H:i:s') , " Rename second worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Terms and conditions');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
*/
