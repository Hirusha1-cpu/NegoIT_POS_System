<?php
  include_once  'template/m_header.php';
  $total_sale=array_sum($cat_sale);
  $decimal = getDecimalPlaces(1);
  $components = $_GET['components'];
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($cu_name0);$x++){ print '"'.$cu_name0[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});

	function setCustID(){
		var id_arr = [<?php for ($x=0;$x<sizeof($cu_id0);$x++){ print '"'.$cu_id0[$x].'",'; } ?>	];
		var name_arr = [<?php for ($x=0;$x<sizeof($cu_name0);$x++){ print '"'.$cu_name0[$x].'",'; } ?>	];
		var name=document.getElementById('tags1').value;
		if(name!=''){
			var a=name_arr.indexOf(name);
			document.getElementById('customer_id').value=id_arr[a];
		}
		document.getElementById('search_form').submit();
	}
</script>

<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart1);
  function drawChart1() {

    var data = google.visualization.arrayToDataTable([
      ['Category', 'Sales Rate'],
      <?php for($i=0;$i<sizeof($cat_name);$i++){
        $rate=round(($cat_sale[$i]/$total_sale)*100,2);
        if($rate<0) $rate=0;
        print "['$cat_name[$i]',$rate ],";
      } ?>
    ]);

    var options = {
      title: 'Sales By Category',
      pieHole: 0.4,
    };
    var chart = new google.visualization.PieChart(document.getElementById('graph1'));

    chart.draw(data, options);
  }
</script>

<div class="w3-container" style="margin-top:75px">
  <hr>
  <div class="w3-row">
    <div class="w3-col s3"></div>
    <div class="w3-col">
      <div id="print" style="overflow-x: auto;">
        <form action="index.php?components=<?php print $_GET['components']; ?>&action=sales_bycategory" method="POST" onsubmit="return validateDateRange();" id="search_form">
          <input type="hidden" id="customer_id" name="customer_id" value="" />
          <table align="center">
            <tr>
              <td>
                <div style="background-color:#DFDFDF; border-radius:10px; font-family:Calibri">
                  <table align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri">
                    <tr style="height:40px;">
                      <td width="50px"></td>
                      <td width="100px" align="left"><strong>From Date : </strong></td>
                      <td>
                        <input type="date" id="datefrom" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
                      </td>
                      <td width="50px"></td>
                      <td width="100px" align="left"><strong>To Date : </strong></td><td>
                        <input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
                      </td>
                      <td width="50px"></td>
                      <td width="100px" rowspan="2"><input type="button" value="GET" onclick="setCustID()" style="height: 40px; width: 60px;"/></td>
                    </tr>
                    <tr style="height:40px;">
                      <td width="50px"></td>
                      <td width="100px" align="left"><strong>Salesman : </strong></td>
                      <td>
                        <select id="salesman" name="salesman">
                                        <option value="all">--ALL--</option>
                                        <?php
                                            $salesmanname='ALL';
                                            for($i=0;$i<sizeof($up_id);$i++){
                            if(isset($_REQUEST['salesman'])){
                              if($up_id[$i]==$_REQUEST['salesman']){
                                $select='selected="selected"'; $salesmanname=ucfirst($up_name[$i]);
                              }else{
                                $select='';
                              }
                            }else{
                                                    $select='';
                                                }
                                                print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
                                            }
                                        ?>
                                        </select>
                      </td>
                      <td width="50px"></td>
                      <td width="80px" align="left"><strong>Customer : </strong></td>
                      <td width="250px">
                        <input type="text" id="tags1" value="<?php print $customer; ?>" onclick="this.value=''" />
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </form>
      </div>
      <br />
      <?php if(sizeof($cat_name)>0)
        print '<table width="600px" style="overflow-x: auto;" align="center">
            <tr>
              <td>
                <div id="graph1" style="width: 100%; height: 350px;"></div>
              </td>
            </tr>
          </table>';
      ?>

      <table align="center" style="font-size:12pt; font-family:Calibri">
        <tr bgcolor="#467898" style="color:white">
          <th width="60px">#</th>
          <th>Category</th>
          <th width="100px">Sale</th>
        </tr>
        <?php for($i=0;$i<sizeof($cat_name);$i++){
            if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
            print '<tr style="background-color:'.$color.'">
                    <td align="center">
                      '.($i+1).'
                    </td>
                    <td class="shipmentTB3">'.$cat_name[$i].'</td>
                    <td class="shipmentTB3" align="right">'.number_format($cat_sale[$i], $decimal).'</td>
                  </tr>';
          }
          print '<tr style="background-color:#DDDDDD">
                  <td></td>
                  <td class="shipmentTB3"><strong>Total</strong></td>
                  <td class="shipmentTB3" align="right"><strong>'.number_format($total_sale, $decimal).'</strong></td>
              </tr>';
        ?>
      </table>
    </div>
  </div>
</div>

<hr>
<br />

<?php
  include_once  'template/m_footer.php';
?>
