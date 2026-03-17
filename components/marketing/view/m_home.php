<?php
    include_once  'template/m_header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script
    src="https://maps.googleapis.com/maps/api/js?key=<?php print $map_api; ?>">
</script>
<script type="text/javascript">

    function initMap(){
        var center = {lat: <?php print $x_center; ?>, lng: <?php print $y_center; ?>};
        var locations = [  <?php for($i=0;$i<sizeof($map_cust);$i++){	print "['Customer : $map_cust[$i]',   $map_x[$i], $map_y[$i]],";	} ?>  ];
        
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: center
        });
        var infowindow =  new google.maps.InfoWindow({});
        var marker, count;
	
	
        for (count = 0; count < locations.length; count++) {
            //var iconCounter = pointers[count];
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[count][1], locations[count][2]),
            map: map,
            title: locations[count][0]
            });
            google.maps.event.addListener(marker, 'click', (function (marker, count) {
            return function () {
                infowindow.setContent(locations[count][0]);
                infowindow.open(map, marker);
            }
            })(marker, count));
        }
	}
    $(function() {
		var availableTags0 = [<?php for ($x=0;$x<sizeof($town_id);$x++){ print '"'.$town_name[$x].'",'; } ?>	];
		$( "#town" ).autocomplete({
			source: availableTags0
		});
	});

    function setTown($tw_name){
		document.getElementById('town').value=$tw_name;
		setFilter();
	}

 	function setFilter(){
        var sub_sys=document.getElementById('sub_sys').value; 
 		var st=document.getElementById('st').value; 
 		<?php if($_REQUEST['components'] == 'marketing'){ ?> var sm=document.getElementById('sm').value; <?php } ?>
 		var gp=document.getElementById('gp').value; 
 		var town=document.getElementById('town').value; 
 		document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
 		<?php if($_REQUEST['components'] == 'marketing'){ ?>
 			window.location = 'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=mk_home&sub_sys='+sub_sys+'&st='+st+'&sm='+sm+'&gp='+gp+'&town='+town;
		<?php }else{ ?>
			window.location = 'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=mk_home&sub_sys='+sub_sys+'&st='+st+'&gp='+gp+'&town='+town;
		<?php } ?>
    }
      	
 	function getCustMore($id){
 	  document.getElementById('div_custmore_'+$id).innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    	$values=returntext.split('|');
			document.getElementById('c_name').innerHTML=$values[0];
			document.getElementById('c_mobile').innerHTML=$values[1];
			document.getElementById('c_tel').innerHTML=$values[2];
			document.getElementById('c_st').innerHTML=$values[3];
			document.getElementById('c_sm').innerHTML=$values[4];
			document.getElementById('c_gp').innerHTML=$values[5];
			document.getElementById('c_subsys').innerHTML=$values[6];
			document.getElementById('c_cname').innerHTML=$values[8];
			document.getElementById('c_address').innerHTML=$values[9];
			document.getElementById('c_email').innerHTML=$values[10];
            <?php if($_REQUEST['components'] == 'marketing'){ ?>
			document.getElementById('c_crlimit').innerHTML=thousands_separators($values[11]);
			document.getElementById('c_crbalance').innerHTML=thousands_separators($values[13]);
			document.getElementById('c_outstanding').innerHTML=thousands_separators($values[14]);
			document.getElementById('c_1ysale').innerHTML=thousands_separators($values[15]);
			document.getElementById('c_linvoice').innerHTML=$values[17].padStart(7, '0')+'&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#3366CC">'+$values[16]+'</span>';
			document.getElementById('c_lpayment').innerHTML=$values[19].padStart(7, '0')+'&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#3366CC">'+$values[18]+'</span>';
			document.getElementById('c_retchq').innerHTML=$values[20];
			document.getElementById('c_poschq').innerHTML=$values[21];
			document.getElementById('c_depchq').innerHTML=$values[22];
			if($values[7]==0 ){
				document.getElementById('c_mcust').innerHTML='';
			}else{
				document.getElementById('c_mcust').innerHTML='<span style="color:#3366CC">YES</span>&nbsp;&nbsp;&nbsp;<input type="button" value="Customer Report" onclick="window.open(\'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=cust_sale&customer_id='+$values[7]+'&datefrom='+$values[23]+'&dateto='+$values[24]+'\',\'_blank\')" />';
			}
            <?php } ?>

			if($values[12]=='no'){
				document.getElementById('c_gps').innerHTML='';
			}else{
				document.getElementById('c_gps').innerHTML='<a href="https://maps.google.com/?q='+$values[12]+'" target="_blank">Open on Map</a>';
			}
 	  		document.getElementById('div_custmore_'+$id).innerHTML='<input type="button" value="Get" onclick="getCustMore('+$id+')" />';
	    }
	  };
	  xhttp.open("GET", 'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=get_cust_more&id='+$id, true);
	  xhttp.send();
 	}
</script>

<div class="w3-container" style="margin-top:75px">
    <div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px;" /></div>
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <table align="center" style="font-family:Calibri; font-size:12pt">
                <tr>
                    <td>
                        <div style="background-color:#EEEEEE; border-radius:10px">
                            <table>
                                <tr height="50px">
                                    <td width="20px"></td>
                                    <td>Sub-System</td>
                                    <td>
                                        <select id="sub_sys" onchange="setFilter()">
                                            <?php if($_REQUEST['components'] == 'marketing'){ ?>
                                            <option value="all" >--ALL--</option>
                                            <?php } ?>
                                            <?php
                                            if($_REQUEST['components'] == 'marketing' )$head_sub_sys='ALL';
                                            for($i=0;$i<sizeof($subsys_id);$i++){
                                                if($subsys_id[$i]==$set_sub_sys){ $select='selected="selected"'; $head_sub_sys=$subsys_name[$i]; }else{ $select=''; }
                                                print '<option value="'.$subsys_id[$i].'" '.$select.'>'.$subsys_name[$i].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20px"></td>
                                    <td>Store</td>
                                    <td>
                                        <select id="st" onchange="setFilter()">
                                        <?php if($_REQUEST['components'] == 'marketing'){ ?>
                                        <option value="all" >--ALL--</option>
                                        <?php } ?>
                                        <?php
                                        if($_REQUEST['components'] == 'marketing' )$head_store='ALL';
                                        for($i=0;$i<sizeof($st_id);$i++){
                                            if($st_id[$i]==$set_store){ $select='selected="selected"'; $head_store=$st_name[$i]; }else{ $select=''; }
                                            print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
                                        }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php if($_REQUEST['components'] == 'marketing'){ ?>
                                <tr>
                                    <td width="20px"></td>
                                    <td>Salesman</td><td>
                                        <select id="sm" onchange="setFilter()">
                                        <?php if($_REQUEST['components'] == 'marketing'){ ?>
                                        <option value="all" >--ALL--</option>
                                        <?php } ?>
                                        <?php
                                        if($_REQUEST['components'] == 'marketing' )$head_salesman='ALL';
                                        for($i=0;$i<sizeof($sm_id);$i++){
                                            if($sm_id[$i]==$set_salesman){ $select='selected="selected"'; $head_salesman=$sm_name[$i]; }else{ $select=''; }
                                            print '<option value="'.$sm_id[$i].'" '.$select.'>'.$sm_name[$i].'</option>';
                                        }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td width="20px"></td>
                                    <td>Group</td><td>
                                        <select id="gp" onchange="setFilter()">
                                        <option value="all" >--ALL--</option>
                                        <?php
                                        $head_group='ALL';
                                        for($i=0;$i<sizeof($gp_id);$i++){
                                            if($gp_id[$i]==$set_group){ $select='selected="selected"'; $head_group=$gp_name[$i]; }else{ $select=''; }
                                            print '<option value="'.$gp_id[$i].'" '.$select.'>'.$gp_name[$i].'</option>';
                                        }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20px"></td>
                                    <td>Town</td>
                                    <td>
                                        <input type="text" style="width:100px" id="town" value="<?php print $set_town; ?>" /> 
                                    </td>
                                    <td>
                                        <div id="div_submit"><input type="button" value="Submit" onclick="setFilter()" style="width:60px; height:35px;" /></div>
                                    </td>
                                    <td width="20px"></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <table align="center" style="font-family:Calibri; font-size:12pt" width="100%">
                <tr style="background-color:#467898; color:white;" class="shipmentTB3">
                    <th>Town</th>
                    <th class="shipmentTB3">Customer<br />Count</th>
                </tr>
                <?php
                    for($i=0;$i<sizeof($town_id);$i++){
                        if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
                        if($town_name[$i]==$set_town){ $bold='font-weight:bold;'; $color='#CCCCCC'; }else $bold='';
                        print '<tr style="background-color:'.$color.'; '.$bold.'"><td class="shipmentTB3" style="color:blue"><a style="cursor:pointer; padding-left:20px; padding-right:20px;" onclick="setTown('."'$town_name[$i]'".')">'.$town_name[$i].'</a></td><td align="center">'.$town_cust_count[$i].'</td></tr>';
                    }
                        print '<tr style="background-color:#DDDDDD; font-weight:bold;"><td class="shipmentTB3" style="color:blue">Total Count</td><td align="center"><strong>'.number_format(array_sum($town_cust_count)).'</strong></td></tr>';
                ?>
            </table>
            <br>
            <table align="center" style="font-family:Calibri; font-size:12pt" width="100%">
                <tr style="background-color:#467898; color:white; padding-left:20px; padding-right:20px;">
                    <th colspan="2">Customer</th>
                    <th>Mobile</th>
                    <th>Shop Tel</th>
                    <th>More Details</th>
                </tr>
                <?php
                    for($i=0;$i<sizeof($cu_id);$i++){
                        if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
                        print '<tr style="background-color:'.$color.'">
                                    <td><input type="checkbox" /></td>
                                    <td class="shipmentTB4" style="color:blue">'.$cu_name[$i].'</td>
                                    <td align="center" class="shipmentTB3"><a href="tel:'.$cu_mobile[$i].'" class="taptocall">'.$cu_mobile[$i].'</a></td>
                                    <td align="center" class="shipmentTB3"><a href="tel:'.$cu_shop_tel[$i].'" class="taptocall">'.$cu_shop_tel[$i].'</a></td>
                                    <td align="center" class="shipmentTB3"><div id="div_custmore_'.$cu_id[$i].'"><input type="button" value="Get" onclick="getCustMore('.$cu_id[$i].')" /></div></td>
                                </tr>';
                    }
                ?>
            </table>
            <br>
            <table border="0" cellspacing="0" style="font-family:Calibri; font-size:12pt" width="100%">
                <tr bgcolor="#E2E9EF"><td class="shipmentTB3" colspan="3" style="color:#3366CC; font-size:14pt"><strong><div id="c_name" ></div></strong></td></tr>
                <tr><td class="shipmentTB3"><strong>Mobile</strong></td><td>: </td><td class="shipmentTB3"><div id="c_mobile" ></div></td></tr>
                <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Shop Tel</strong></td><td>: </td><td class="shipmentTB3"><div id="c_tel" ></div></td></tr>
                <tr><td class="shipmentTB3"><strong>Shop</strong></td><td>: </td><td class="shipmentTB3"><div id="c_st" ></div></td></tr>
                <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Salesman</strong></td><td>: </td><td class="shipmentTB3"><div id="c_sm" ></div></td></tr>
                <tr><td class="shipmentTB3"><strong>Group</strong></td><td>: </td><td class="shipmentTB3"><div id="c_gp" ></div></td></tr>
                <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Sub System</strong></td><td>: </td><td class="shipmentTB3"><div id="c_subsys" ></div></td></tr>
                <?php if($_REQUEST['components'] == 'marketing'){ ?>
                <tr><td class="shipmentTB3"><strong>Master Cust</strong></td><td>: </td><td class="shipmentTB3"><div id="c_mcust" ></div></td></tr>
                <?php } ?>
                <tr><td class="shipmentTB3" colspan="3"><hr /></td></tr>
                <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Customer Name</strong></td><td>: </td><td class="shipmentTB3"><div id="c_cname" ></div></td></tr>
                <tr><td class="shipmentTB3"><strong>Shop Address</strong></td><td>: </td><td class="shipmentTB3"><div id="c_address" ></div></td></tr>
                <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Email</strong></td><td>: </td><td class="shipmentTB3"><div id="c_email" ></div></td></tr>
                <tr><td class="shipmentTB3"><strong>GPS</strong></td><td>: </td><td class="shipmentTB3"><div id="c_gps" ></div></td></tr>
                <?php if($_REQUEST['components'] == 'marketing'){ ?>
                    <tr><td class="shipmentTB3" colspan="3"><hr /></td></tr>
                    <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Credit Limit</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_crlimit" ></div></td></tr>
                    <tr><td class="shipmentTB3"><strong>CR Limit Balance</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_crbalance" ></div></td></tr>
                    <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Outstanding</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_outstanding" ></div></td></tr>
                    <tr><td class="shipmentTB3"><strong>Last 1Year Sale</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_1ysale" ></div></td></tr>
                    <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Last Invoice</strong></td><td>: </td><td class="shipmentTB3"><div id="c_linvoice" ></div></td></tr>
                    <tr><td class="shipmentTB3"><strong>Last Payment</strong></td><td>: </td><td class="shipmentTB3"><div id="c_lpayment" ></div></td></tr>
                    <tr><td class="shipmentTB3" colspan="3"><hr /></td></tr>
                    <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Returned Chques</strong></td><td>: </td><td class="shipmentTB3" style="color:red; font-weight:bold;"><div id="c_retchq" ></div></td></tr>
                    <tr><td class="shipmentTB3"><strong>Postponed Chques</strong></td><td>: </td><td class="shipmentTB3" style="color:red; font-weight:bold;"><div id="c_poschq" ></div></td></tr>
                    <tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Deposited Chques</strong></td><td>: </td><td class="shipmentTB3" style="color:blue; font-weight:bold;"><div id="c_depchq" ></div></td></tr>
                <?php } ?>
            </table>
            <br />
	        <br />
	        <div id="map" style="width:100%; height:500px"></div>
        </div>
    </div>
</div>
<br><br>
<script type="text/javascript">
    function initMap(){
	  var center = {lat: <?php print $x_center; ?>, lng: <?php print $y_center; ?>};
	  var locations = [  <?php for($i=0;$i<sizeof($map_cust);$i++){	print "['Customer : $map_cust[$i]',   $map_x[$i], $map_y[$i]],";	} ?>  ];
	   
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 12,
			center: center
		});
		var infowindow =  new google.maps.InfoWindow({});
		var marker, count;
	
		for (count = 0; count < locations.length; count++) {
			//var iconCounter = pointers[count];
			marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[count][1], locations[count][2]),
			map: map,
			title: locations[count][0]
			});
			google.maps.event.addListener(marker, 'click', (function (marker, count) {
			return function () {
				infowindow.setContent(locations[count][0]);
				infowindow.open(map, marker);
			}
			})(marker, count));
	  	}
	}

	$(document).ready( function() { 
        initMap(); 
    });
</script>
<?php
    include_once  'template/m_footer.php';
?>