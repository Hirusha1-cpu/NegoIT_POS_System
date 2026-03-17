<?php
  include_once  'template/header.php';
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart1);
  function drawChart1() {

    var data = google.visualization.arrayToDataTable([
      ['Suplier', 'Pending Amount'],
      <?php for($i=0;$i<sizeof($supplier_name);$i++){
        print "['$supplier_name[$i]',$supplier_remaining[$i] ],";
      } ?>
    ]);

    var options = {
      title: 'Supplier Pending Payments',
      pieHole: 0.4,
    };
    var chart = new google.visualization.PieChart(document.getElementById('supplier_pay'));

    chart.draw(data, options);
  }
  //------------------------------------------------------------//
  google.charts.setOnLoadCallback(drawChart2);
  function drawChart2() {

    var data = google.visualization.arrayToDataTable([
      ['Account', 'Expense Amount'],
      <?php for($i=0;$i<sizeof($expense_account);$i++){
        print "['$expense_account[$i]',$expense_amount[$i] ],";
      } ?>
    ]);

    var options = {
      title: 'This Year Expenses',
      pieHole: 0.4,
    };
    var chart = new google.visualization.PieChart(document.getElementById('expense_pay'));

    chart.draw(data, options);
  }
</script>

<script>
  var $total=0;
  function getBalance($account){
		$balance = document.getElementById($account);
		$total_td = document.getElementById("total");
    document.querySelectorAll('.accounts').forEach(elem => {
        elem.disabled = true;
    });
		if($account != ''){
			$balance.innerHTML=document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
	  		xmlhttp.onreadystatechange = function() {
		    	if (this.readyState == 4 && this.status == 200) {
		    		var returntext=this.responseText;
					if(returntext!=''){
						$balance.innerHTML=returntext;
            window.$total = window.$total + parseInt(returntext.toLocaleString('en').replace(/\,/g,''), 10);
            document.querySelectorAll('.accounts').forEach(elem => {
              elem.disabled = false;
            });
            $total_td.innerHTML= "TOTAL : "+window.$total.toLocaleString('en');
					}else{
						$balance.innerHTML=""
            document.querySelectorAll('.accounts').forEach(elem => {
              elem.disabled = false;
            });
					}
		    	}
	  		};
			$currentDate = new Date(+new Date().setHours(0, 0, 0,0)+ 86400000).toLocaleDateString('fr-CA');
			xmlhttp.open("GET", 'index.php?components=<?php print $_GET['components']; ?>&action=account_balance&method=ajax&from_date='+$currentDate+'&to_date='+$currentDate+'&id='+$account, true);
			xmlhttp.send();
		}else{
			$balance.innerHTML=""
		}
	}
</script>

<style>
	table{
		font-family:Calibri;
	}
	.tbl-header{
		font-family:Calibri; 
		color:maroon; 
		font-weight:bold; 
		background:#EEEEEE;
		width: 800px;
	}
	.td-style{
		background-color:silver; 
		color:navy; 
		font-family:Calibri; 
		font-size:14pt;
	}
	.styled-table {
		border-collapse: collapse;
		margin-top: 25px;
		font-family:Calibri;
		min-width: 400px;
		box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
	}
	.styled-table thead tr {
		background-color: #3f83d7;
		color: #ffffff;
		text-align: left;
	}
	.styled-table th,
	.styled-table td {
		padding: 5px 15px;
	}

	.styled-table tbody tr {
    	border-bottom: thin solid #dddddd;
	}

	.styled-table tbody tr:nth-of-type(even) {
		/* background-color: #f3f3f3; */
	}

	.styled-table tbody tr:last-of-type {
		border-bottom: 2px solid #205081;
	}

	.styled-table tbody tr:hover {background-color: #f3f3f3;}
</style>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px;"/></div>
<!-- Notifications -->
<table align="center" style="font-size:12pt">
  <tr>
    <td>
      <?php 
        if(isset($_REQUEST['message'])){
          if($_REQUEST['re']=='success') $color='green'; else $color='red';
            print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
        }?>
    </td>
  </tr>
</table>
<!--/ Notifications -->

<!-- Accounts Balances -->
<div>
  <table align="center" border="0"  class="styled-table" width="720px">
    <thead>
      <tr>
        <td colspan="5" style="color: black; background: #dddddd;" class="td-style"><strong style="padding-left: 10px">Accounts Balances</strong></td>
      </tr>
      <tr>
        <th width="20px">#</th>
        <th width="120px" align="center">Account Name</th>
        <th width="100px" align="center">Balance</th>
      </tr>
    </thead>
    <tbody>
    
        <?php for($i=0;$i<sizeof($fromac_id);$i++){
          print '<tr>
                  <td>'.($i+1).'</td>
                  <td align="left">'.$fromac_name[$i].'</td>
                  <td align="right" id="'.$fromac_id[$i].'"><button class="accounts" onclick=getBalance('.$fromac_id[$i].')>GET</button></td>
              </tr>';
          } 
          ?>
          <tr>
            <td colspan="3" style="font-weight:bold;" id="total" align="right">TOTAL : 0.00</td>
          </tr>
    </tbody>
  </table>
</div>
<!--/ Accounts Balances  -->

<table align="center">
	<tr>
		<td><div id="supplier_pay" style="width: 600px; height: 300px;"></div></td>
		<td><div id="expense_pay" style="width: 600px; height: 300px;"></div></td>
	</tr><tr>
		<td align="center" valign="top">
			<table style="font-family:Calibri; font-size:10pt" width="80%">
				<tr bgcolor="#8898A1" style="color:white"><th colspan="5">Latest Invoices from Suppliers</th></tr>
				<tr bgcolor="#467898" style="color:white"><th>Supplier</th><th>Invoice No</th><th>Invoice Date</th><th>Due Date</th><th>Amount</th></tr>
				<?php for($i=0;$i<sizeof($supinv_shipno);$i++){
						if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
					print '<tr style="background-color:'.$color.'"><td class="shipmentTB3">'.$supinv_name[$i].'</td><td class="shipmentTB3"><a href="index.php?components=inventory&action=one_shipment&shipment_no='.$supinv_shipno[$i].'">'.$supinv_invno[$i].'</a></td><td align="center">'.$supinv_invdate[$i].'</td><td align="center">'.$supinv_duedate[$i].'</td><td align="right" class="shipmentTB3">'.number_format($supinv_amount[$i]).'</td></tr>';
				}				
				?>
			</table>
		</td>
		<td align="center" valign="top">
			<table style="font-family:Calibri; font-size:10pt" width="70%">
				<tr bgcolor="#8898A1" style="color:white"><th colspan="2">Pending Customer Credit</th></tr>
				<tr bgcolor="#467898" style="color:white"><td class="shipmentTB3">Customer Account</td><td width="100px" class="shipmentTB3" align="right">Amount</td></tr>
				<?php for($i=0;$i<sizeof($date);$i++){
				if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
				print '<tr bgcolor="'.$color.'"><td class="shipmentTB3"><a href="index.php?components=manager&action=cust_sale&customer='.$payee[$i].'&datefrom='.$backdate30.'&dateto='.$today.'">'.$payee[$i].'</a></td><td class="shipmentTB3" align="right">'.number_format($dr[$i]).'</td></tr>';
				} ?>
			</table>
		</td>
	</tr>
</table>
	
<?php
  include_once  'template/footer.php';
?>