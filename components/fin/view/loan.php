<?php
                include_once  'template/header.php';
?>

	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<?php if($_GET['action']=='loan'){ ?>
	<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($emp_name);$x++){ print '"'.ucfirst($emp_name[$x]).'",'; } ?>	];
		$( "#emp_name" ).autocomplete({
			source: availableTags1
		});
	});
	function loanInstallment($PV,$r,$n){
		if($r==0){
			$P=$PV/$n;
		}else{
			$r=$r/100/12;
			$P=($r*$PV)/(1-(Math.pow((1+$r),-$n)));
			$P=Math.round($P,2);
		}
		return $P;
	}
	function calculate(){
		var loan_amount=parseInt(document.getElementById('amount').value);
		var rate=document.getElementById('rate').value;
		var duration=parseInt(document.getElementById('duration').value);
		var $factor=12;
		var installment=loanInstallment(loan_amount,rate,duration);
		$return=installment*duration;
		document.getElementById('totalreturn').value=$return;	
		document.getElementById('installment').value=installment;	
	}
</script>
<?php } ?>
<!-- --------------------------------------------------------------------------------------------------------------------- -->
<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>

<table align="center" border="0"  style="font-size:12pt">
<tr><td valign="top" align="center"><?php
	if($_GET['action']=='loan')include_once  'components/fin/view/tpl/new_loan.php';
	if($_GET['action']=='loan_one')include_once  'components/fin/view/tpl/one_loan.php';
?></td><td width="100px"></td><td valign="top">
	<table>
	<tr style="background-color:#467898;color :white;" ><th class="shipmentTB3">Employee</th><th class="shipmentTB3">Loan Amount</th><th class="shipmentTB3">Start Date</th><th class="shipmentTB3">End Date</th><th class="shipmentTB3">Balance</th><th class="shipmentTB3">Status</th></tr>
	<?php for($i=0;$i<sizeof($loan_id);$i++){
		if($loan_status[$i]=='Inactive') $color1='grey'; else $color1='black';
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		if($loan_id[$i]=='newstatus')
		print '<tr style="color:navy"><td class="shipmentTB3" colspan="6"><br /><hr /><strong>List of Last 30 Finalized Loans</strong></td></tr>';
		else
		print '<tr bgcolor="'.$color.'" style="color:'.$color1.'"><td class="shipmentTB3"><a href="index.php?components=fin&action=loan_one&id='.$loan_id[$i].'" style="text-decoration:none">'.ucfirst($loan_emp[$i]).'</a></td><td class="shipmentTB3">'.number_format($loan_amount[$i],2).'</td><td class="shipmentTB3" align="right">'.$loan_start[$i].'</td><td class="shipmentTB3" align="right">'.$loan_end[$i].'</td><td class="shipmentTB3" align="right">'.number_format($loan_balance[$i],2).'</td><td class="shipmentTB3">'.$loan_status[$i].'</td></tr>';
	} ?>
	</table>
</td valign="top"></tr>
</table>
	

<?php
                include_once  'template/footer.php';
?>