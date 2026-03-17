   <select name="district" id="district" onchange="setDistrict2('bill2')" style="font-size:large;" <?php if($static_district!=0) print 'disabled="disabled"'; ?> >
	<option>-SELECT District-</option>
<?php for($i=0;$i<sizeof($district_id);$i++){
		if($current_district==$district_id[$i]){
			$select='selected="selected"'; 
			$style='style="color:red; font-weight:bold;"';
		}else{
			$select='';
			$style='';
		}
		print '<option '.$select.' '.$style.'  value="'.$district_id[$i].'">'.$district_name[$i].'</option>';
		}
		print '</select>';
 ?>
