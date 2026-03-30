<?php
                include_once  'template/header.php';
?>
	
	<script type="text/javascript">
		function calculateSalary($id){
			var $subtotal=0;
			$basic_total=document.getElementById('basic_total_'+$id).value;
			$commission=document.getElementById('commission_'+$id).value;
			$special=document.getElementById('special_'+$id).value;
			$total=parseInt($basic_total)+parseInt($commission)+parseInt($special);
			document.getElementById('total_'+$id).innerHTML=$total;
			document.getElementById('basic_total_cal_'+$id).value=$total;
			$arr_size=document.getElementById('arr_size').value;
			for($i=0;$i<$arr_size;$i++){
				var $subtotal=parseInt($subtotal)+parseInt(document.getElementById('basic_total_cal_'+$i).value);
			}
			document.getElementById('sub_total').innerHTML=$subtotal;
		}
	</script>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<table align="center" width="900px" cellspacing="0" ><tr style="background-color:#EEEEEE; font-family:Calibri; font-weight:bold; color:#467898"><td align="center"></td><td width="180px" align="right"><input type="button" value="New Payroll" style="width:150px; height:40px" onclick="window.location = 'index.php?components=fin&action=payroll'" /></td></tr></table>
<br/>
<table align="center" style="font-family:Calibri">
<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" width="200px">Month</th><th class="shipmentTB3" width="100px">Payroll No</th><th class="shipmentTB3" width="100px">Amount</th></tr>
<?php
	for($i=0;$i<sizeof($payroll_no);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td class="shipmentTB3">'.$payroll_month[$i].'</td><td align="center"><a href="index.php?components=fin&action=payroll_one&id='.$payroll_no[$i].'">'.str_pad($payroll_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3" align="right">'.number_format($payroll_amount[$i]).'</td></tr>';
	}
?>
</table>
<?php
                include_once  'template/footer.php';
?>