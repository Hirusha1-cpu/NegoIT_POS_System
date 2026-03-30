function setDistrict($components){
	var id=document.getElementById('district').value;
	if(id!='')
	window.location = 'index.php?components='+$components+'&action=set_district&id='+id;
}

function newBill(){
	var cust=document.getElementById('tags3').value;
	var salesman=document.getElementById('salesman').value;
	if(cust!='')
	window.location = 'index.php?components=billing&action=new_bill&cust='+cust+'&salesman='+salesman;
}

function setPayment(){
	document.getElementById('payment_type').value=0;
	var invoicetotal=parseFloat(document.getElementById('invoicetotal').value);
	var crbalance=parseFloat(document.getElementById('crbalance').value);
	if(document.getElementById('amount_cash').value!='') var amount_cash=(document.getElementById('amount_cash').value); else var amount_cash=0;
	if(document.getElementById('amount_chque').value!='')var amount_chque=(document.getElementById('amount_chque').value); else var amount_chque=0;
	if(document.getElementById('amount_credit').value!='')var amount_credit=(document.getElementById('amount_credit').value); else var amount_credit=0;
	var balance=((invoicetotal * 10)-(amount_cash * 10)-(amount_credit * 10)-(amount_chque* 10))/10;
	document.getElementById('payment_balance_div').innerHTML=balance; 
	document.getElementById('payment_cash_div').innerHTML=amount_cash;
	document.getElementById('payment_chque_div').innerHTML=amount_chque;
	document.getElementById('payment_credit_div').innerHTML=amount_credit;
	document.getElementById('balance').value=balance;
	if((amount_cash>0)&&(amount_chque>0)) document.getElementById('payment_type').value=3; else
	if(amount_cash>0) document.getElementById('payment_type').value=1; else
	if(amount_chque>0) document.getElementById('payment_type').value=2;
	document.getElementById('cr_balance_div').innerHTML=crbalance-amount_credit;
}

function deleteGRN(id){
	var check= confirm("Do you really want to Delete this GRN?");
 if (check== true)
	document.getElementById('deletegrn').innerHTML=''; 
	window.location = 'index.php?components=trans&action=delete&id='+id;
}

function updateGRN($id){
	var itemid="grnitemid"+$id;
	var qty=document.getElementById(itemid).value;
	window.location = 'index.php?components=trans&action=grn_item_gpdate&id='+$id+'&qty='+qty;
}

function removeGRN($id){
	window.location = 'index.php?components=trans&action=grn_item_remove&id='+$id;
}

function updateShipment($id){
	var itemid="shipitemid"+$id;
	var qty=document.getElementById(itemid).value;
	window.location = 'index.php?components=inventory&action=shipment_item_gpdate&id='+$id+'&qty='+qty;
}

function removeShipment($id){
	window.location = 'index.php?components=inventory&action=shipment_item_remove&id='+$id;
}

function updateBill($id){
	var itemid="billitemid"+$id;
	var qty=document.getElementById(itemid).value;
	var cust=document.getElementById('cust').value;
	var salesman=document.getElementById('salesman').value;
	window.location = 'index.php?components=billing&action=bill_item_gpdate&id='+$id+'&qty='+qty+'&s='+salesman+'&cust='+cust;
}

function removeBill($id){
	var cust=document.getElementById('cust').value;
	var salesman=document.getElementById('salesman').value;
	window.location = 'index.php?components=billing&action=bill_item_remove&id='+$id+'&s='+salesman+'&cust='+cust;
}


function print_bill($id){
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","index.php?components=billing&action=sms&id="+$id,true);
	xmlhttp.send();
	document.getElementById('invoice_iframe').focus();
	document.getElementById('invoice_iframe').contentWindow.print();
}

function print_bill2(printpage,$id){
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","index.php?components=billing&action=sms&id="+$id,true);
	xmlhttp.send();
	var headstr = "<html><head><title></title></head><body>";
	var footstr = "</body></html>";
	var newstr = document.all.item(printpage).innerHTML;
	var oldstr = document.body.innerHTML;
	document.body.innerHTML = headstr+newstr+footstr;
	window.print();
	document.body.innerHTML = oldstr;
	return false;
}

function printdiv(printpage,header){
	var headstr = "<html><head><title></title></head><body>";
	var footstr = "</body></html>";
	var headerstr = document.all.item(header).innerHTML;
	var newstr = document.all.item(printpage).innerHTML;
	var oldstr = document.body.innerHTML;
	document.body.innerHTML = headstr+headerstr+newstr+footstr;
	window.print();
	document.body.innerHTML = oldstr;
	return true;
}


function approveGRN(id){
	var check= confirm("Do you want to Approve this GTN?");
 if (check== true)
 	document.getElementById('approvegrn').innerHTML=''; 
	window.location = 'index.php?components=trans&action=approve&id='+id;
}


function rejectGRN(id){
	var check= confirm("Do you really want to Reject this GTN?");
 if (check== true)
 	document.getElementById('rejectgrn').innerHTML=''; 
	window.location = 'index.php?components=trans&action=reject&id='+id;
}

function disableCust(id){
	var check= confirm("Do you really want Deactivate this Customer?");
 if (check== true)
	window.location = 'index.php?components=manager&action=disable_cust&id='+id;
}

function enableCust(id){
	var check= confirm("Do you really want Activate this Customer?");
 if (check== true)
	window.location = 'index.php?components=manager&action=enbale_cust&id='+id;
}

function disableUser(id){
	var check= confirm("Do you really want Deactivate this User?");
 if (check== true)
	window.location = 'index.php?components=settings&action=disable_user&id='+id;
}

function enableUser(id){
	var check= confirm("Do you really want Activate this User?");
 if (check== true)
	window.location = 'index.php?components=settings&action=enbale_user&id='+id;
}

function validateBill(){
	var $count=0;
	var $fastprint=document.getElementById('fastprint').value;
	var $billitemcount=document.getElementById('billitemcount').value;
	var $msg="All fields must be filled out";
	document.getElementById('qty').value=document.getElementById('qty1').value;
    if(document.getElementById('tags1').value=='') $count++;
    if(document.getElementById('tags2').value=='') $count++;
    if(document.getElementById('qty').value=='') $count++;
    if(document.getElementById('cust').value==0){ $count++; if($count==1) $msg="Customer must be Selected!"; }
    if ($count!=0) {
        alert($msg);
        return false;
    }else{
		if(($fastprint=='on')&&($billitemcount==0)){
			printdiv('print1','printheader'); 
			document.getElementById('addtobill').innerHTML=''; 
			document.getElementById("billingForm").submit();
		}
		document.getElementById('addtobill').innerHTML=''; 
        return true;
    }
}

function validateCust(){
	var $count=0;
	var $msg="All fields must be filled out";
    if(document.getElementById('shop_name').value=='') $count++;
    if(document.getElementById('nic').value=='') $count++;
    if(document.getElementById('mobile').value=='') $count++;
    if(document.getElementById('cr_limit').value=='') $count++;
    if(document.getElementById('store').value=='') $count++;
    if(document.getElementById('customer').value=='') $count++;
    if(document.getElementById('shop_address').value=='') $count++;
    if(document.getElementById('shop_tel').value=='') $count++;
    if(document.getElementById('home_address').value=='') $count++;
    if(document.getElementById('home_tel').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function validateUser(){
	var $count=0;
	var $msg="All fields must be filled out";
    if(document.getElementById('user_name').value=='') $count++;
    if(document.getElementById('user_pass1').value=='') $count++;
    if(document.getElementById('user_pass2').value=='') $count++;
    if((document.getElementById('user_pass1').value)!=(document.getElementById('user_pass2').value)){
    	var $msg="Password Does Not Match";
    	document.getElementById('user_pass1').value='';
    	document.getElementById('user_pass2').value='';
    	$count++;
    }
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function validateGRN(){
	var $count=0;
	var $msg="All fields must be filled out";
    if(document.getElementById('tags1').value=='') $count++;
    if(document.getElementById('tags2').value=='') $count++;
    if(document.getElementById('qty').value=='') $count++;
    if(document.getElementById('remotestore').value==0){ $count++; if($count==1) $msg="Store must be Selected!"; }
    if ($count!=0) {
        alert($msg);
        return false;
    }else{
    	document.getElementById('addtogtn').innerHTML='';
        return true;
    }
}

function validateAddItem(){
	var $count=0;
	var $msg="Category, Code, Description and Wholesale Price must be filled!";
    if(document.getElementById('category').value=='') $count++;
    if(document.getElementById('code').value=='') $count++;
    if(document.getElementById('description').value=='') $count++;
    if(document.getElementById('w_price').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function updatePermission(id){
	if(document.getElementById(id).checked==true) $permission='add'; else $permission='del';
	window.location = 'index.php?components=settings&action=update_permission&id='+id+'&permission='+$permission;
}

function updateDeviceCheck(id){
	if(document.getElementById(id).checked==true) $devicecheck='add'; else $devicecheck='del';
	window.location = 'index.php?components=settings&action=update_devicecheck&id='+id+'&devicecheck='+$devicecheck;
}

function updateTimeCheck(id){
	if(document.getElementById(id).checked==true) $timecheck='add'; else $timecheck='del';
	window.location = 'index.php?components=settings&action=update_timecheck&id='+id+'&timecheck='+$timecheck;
}

function updateStoreAso(id){
	var store=document.getElementById(id).value;
	window.location = 'index.php?components=settings&action=update_storeaso&id='+id+'&store='+store;
}

function getPaymantData(){
	var cust=document.getElementById('tags2').value;
	if(cust!='')
	window.location = 'index.php?components=billing&action=payment&cust='+cust;
}

function validateDate($date){
	var $count=0;
	var year=$date.substring(0, 4);
	var month=$date.substring(5, 7);
	var date=$date.substring(8, 10);
	var dash1=$date.substring(4, 5);
	var dash2=$date.substring(7, 8);
	if(isNaN(year)) $count++;
	if(isNaN(month)) $count++;
	if(isNaN(date)) $count++;
	if(month>12) $count++;
	if(date>31) $count++;
	if((dash1!='-')||(dash2!='-')) $count++;
 	if ($count!=0) return false; else return true;
}

function validateDateRange(){
	var datefrom=document.getElementById('datefrom').value;
	var dateto=document.getElementById('dateto').value;
	var customer=document.getElementById('tags1').value;
	if((validateDate(datefrom))&&(validateDate(dateto))&&(customer!='')) return true; else{
	 alert('All fields must be filled and Date Must Be in 2010-11-28 Format');
	 return false;
	}
}

function validatePayment($form){
	var $count=0;
    var txt = "";
    var i;
    if($form==1){
        var payment_type = document.forms[0];
	    for (i = 0; i < payment_type.length; i++) {
	        if (payment_type[i].checked) {
	            txt = txt + payment_type[i].value + " ";
	        }
	    }
    }
    if($form==2){
   		if(document.getElementById('balance').value!=0){ $count++; $msg='Balance Must Be 0. Please add Cash, Chque, Credit'; }
   		if(document.getElementById('crbalance').value<0){ $count++; $msg='Credit Limit Exceed'; }
		if(document.getElementById('amount_chque').value>0) var txt=2;
    }
    if(txt==1){
	    if($form==1){
	   		if(document.getElementById('amount_chque').value==''){
	   		 	alert("Amount Shuldn't be Empty");
	   		 	return false;
	   		}else{
				document.getElementById('addpayment').innerHTML=''; 
	   			return true;
	   		}
	    }
	    if($form==2){
	   		if(document.getElementById('amount').value==''){
	   		 	alert("Amount Shuldn't be Empty");
	   		 	return false;
	   		}else	return true;
	    }
    }
    if(($count==0)&&(txt==2)){
    	$msg='Amount, Chque Bank, Branch, Chque No, Chque Date Must be filled';
   		if(!(document.getElementById('amount_chque').value>0)) $count++;
    	if(document.getElementById('chque_no').value=='') $count++;
    	if(document.getElementById('tags4').value=='') $count++;
    	if(document.getElementById('chque_branch').value=='') $count++;
    	if(document.getElementById('chque_date').value=='') $count++;
    	if(validateDate(document.getElementById('chque_date').value)==false){
    		$count++;
    		$msg='Date Must Be in 2010-11-28 Format';
    	}
		if(isNaN(document.getElementById('chque_branch').value)){ 
			$count++;
    		$msg='Branch must be a Number (Branch Code)';
		}
    }
    if(($form==1)&&(txt=='')){
	        alert('Payment Type Must be Selected');
	        return false;
    }

	    if ($count!=0) {
	        alert($msg);
	        return false;
	    }else{
			if($form==1) document.getElementById('addpayment').innerHTML=''; 
			if($form==2) document.getElementById('finalize').innerHTML=''; 
	    	return true;
	    }
}


function deletePayment(id){
	var check= confirm("Do you really want Delete this Payment?");
 if (check== true)
	window.location = 'index.php?components=billing&action=delete_payment&id='+id;
}

function clearReturnChq(id){
	var check= confirm("Do you really want Mark this Chque as Clear?");
 if (check== true)
	window.location = 'index.php?components=billing&action=rtnchque_clear&id='+id;
}

function pendingReturnChq(id){
	var check= confirm("Do you want Mark this Chque as Pending?");
 if (check== true)
	window.location = 'index.php?components=manager&action=rtnchque_pending&id='+id;
}

function deleteReturnChq(id){
	var check= confirm("Do you want to Delete this Return Chque?");
 if (check== true)
	window.location = 'index.php?components=manager&action=rtnchque_delete&id='+id;
}

function validateSpecial(){
	var $count=0;
	var $msg="All fields must be filled out";
    if(document.getElementById('tags1').value=='') $count++;
    if(document.getElementById('district').value=='') $count++;
    if(document.getElementById('increment').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function validateDisSpecial(){
	var $count=0;
	var $msg="Increment must be filled out";
    if(document.getElementById('increment').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function deleteSpecial(id){
	var check= confirm("Do you really want Remove this Special Price?");
 if (check== true)
	window.location = 'index.php?components=inventory&action=delete_specialprice&id='+id;
}

//------------------------Return Item--------------------------------//

function validateReturn(){
	var $count=0;
	var $msg="All fields must be filled out";
    if(document.getElementById('tags1').value=='') $count++;
    if(document.getElementById('tags2').value=='') $count++;
    if(document.getElementById('qty').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }else{
    	document.getElementById('addtoreturn').innerHTML=''; 
        return true;
    }
}

function updateReturn($id){
	var itemid="billitemid"+$id;
	var qty=document.getElementById(itemid).value;
	var cust=document.getElementById('cust').value;
	var invid=document.getElementById('id').value;
	window.location = 'index.php?components=billing&action=return_item_gpdate&return_invoice_no='+invid+'&id='+$id+'&qty='+qty+'&cust='+cust;
}

function removeReturn($id){
	var cust=document.getElementById('cust').value;
	var invid=document.getElementById('id').value;
	window.location = 'index.php?components=billing&action=return_item_remove&return_invoice_no='+invid+'&id='+$id+'&cust='+cust;
}

function deleteReturn(id){
	var check= confirm("Do you really want Delete this Return Invoice?");
 if (check== true)
	window.location = 'index.php?components=billing&action=delete_return&return_invoice_no='+id;
}

function processRtn(id){
	var qtyrtn=document.getElementById('qty'+id).value;
	var invrtn=document.getElementById('inv'+id).value;
	var disrtn=document.getElementById('dis'+id).value;
	var newtotal=parseInt(invrtn) + parseInt(disrtn);
	if(newtotal==qtyrtn)
	window.location = 'index.php?components=order_process&action=process_return&item='+id+'&invrtn='+invrtn+'&disrtn='+disrtn;
	else alert('SUM of Invontory and Disposal should be = to Qty');
}

function moveDisposal(id){
	var check= confirm("Do you really want Move this Item from Disposal to Processing as Pending ?");
	var year=document.getElementById('year').value;
 if (check== true)
	window.location = 'index.php?components=manager&action=move_disposal&year='+year+'&id='+id;
}
//-----------------------------Inventory Different Item----------------------//
function validateQTY(){
	var $count=0;
	var $msg="All fields must be filled out";
    if(document.getElementById('tags1').value=='') $count++;
    if(document.getElementById('qty').value=='') $count++;
    if(document.getElementById('c_price1').value=='') $count++;
    if(document.getElementById('w_price1').value=='') $count++;
    if(document.getElementById('r_price1').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function validateDevice(){
	var $count=0;
	var $msg="Device Name should not be Empty";
    if(document.getElementById('device_name').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function validatePerDevice(){
	var $count=0;
	var $msg="Device and User must be selected";
    if(document.getElementById('per_dev').value=='') $count++;
    if(document.getElementById('per_usr').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}

function delPerDevice($id,$dev,$usr){
	var check= confirm('Do you want to remove permission from '+$dev+' for '+$usr);
 if (check== true)
	window.location = 'index.php?components=settings&action=delpermission_device&id='+$id;
}

function registerDevice($task){
	var device=document.getElementById('dev_id').value;
	if($task=='add'){
	    if(device==''){
	        alert('A Device must be selected');
	        return false;
	    }else{
			window.location = 'index.php?components=manager&action=device_register&device='+device;
	    }
	}
	if($task=='remove'){
			window.location = 'index.php?components=manager&action=device_unregister&device='+device;
	}
}


function validateSup(){
	var $count=0;
	var $msg="Name and Country is Mandatory";
    if(document.getElementById('sup_name').value=='') $count++;
    if(document.getElementById('country').value=='') $count++;
    if ($count!=0) {
        alert($msg);
        return false;
    }
}


function enableSup(id){
	var check= confirm("Do you really want Activate this Supplier?");
 if (check== true)
	window.location = 'index.php?components=purchase_order&action=changest_sup&id='+id+'&status=on';
}

function disableSup(id){
	var check= confirm("Do you really want Deactivate this Supplier?");
 if (check== true)
	window.location = 'index.php?components=purchase_order&action=changest_sup&id='+id+'&status=off';
}

function removeItemPO(id){
	var po_no=document.getElementById('po_no').value;
	var check= confirm("Do you really want to Remove this Item from PO ?");
 if (check== true)
	window.location = 'index.php?components=purchase_order&action=remove_item_po&id='+id+'&po_no='+po_no;
}

function lockPO(){
	var po_no=document.getElementById('po_no').value;
	var check= confirm("Do you really want to Lock PO? Alter locking, you cannot modify the PO");
 if (check== true)
	window.location = 'index.php?components=purchase_order&action=lock_po&po_no='+po_no;
}

function unlockPO(){
	var po_no=document.getElementById('po_no').value;
	var check= confirm("Do you really want to Unlock PO?");
 if (check== true)
	window.location = 'index.php?components=purchase_order&action=unlock_po&po_no='+po_no;
}

function setDateRange(date1,date2){
	var daterange1='<strong>Month / Date</strong>: &nbsp;<input type="date" name="date1" style="width:130px" value="'+date1+'" /><input type="submit" value="GET" />';
	var daterange2='<strong>From </strong>: &nbsp;<input type="date" name="date1" style="width:130px" value="'+date1+'" />&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" name="date2" style="width:130px" value="'+date2+'" /><input type="submit" value="GET" />';
	if(document.getElementById('date_range').checked==true){
		document.getElementById('datediv').innerHTML=daterange2; 
	}else{
		document.getElementById('datediv').innerHTML=daterange1; 
	}
}

function validatePComm(){
	var $count=0;
	var salesman=document.getElementById('tags1').value;
	var from_date=document.getElementById('from_date').value;
	var to_date=document.getElementById('to_date').value;
	var $msg="Name and Country is Mandatory";
    if(salesman=='') $count++;
    if(from_date=='') $count++;
    if(to_date=='') $count++;
    if ($count!=0) {
        alert('Salesman, From Date, To Date must be Filled');
        return false;
    }else{
    	if((validateDate(from_date))&&(validateDate(to_date))) return true
    	else{
	        alert('Invalid Date Format');
	        return false;
    	}
    }
}

function KeyPress(e){
	$id=document.getElementById('id').value;
	$fastprint=document.getElementById('fastprint').value;
    // look for window.event in case event isn't passed in
    e = e || window.event;
    if (e.keyCode == 13){
    	if($fastprint=='on')	printdiv('print','printheader');
    	if($fastprint=='off')	print_bill($id);
    }
}

function KeyPress2(e){
	$id=document.getElementById('id').value;
    // look for window.event in case event isn't passed in
    e = e || window.event;
    if (e.keyCode == 13){
    	print_bill($id);
    }
}

function validateDateRange(){
	var from_date=document.getElementById('from_date').value;
	var to_date=document.getElementById('to_date').value;
	if((validateDate(from_date)) && (validateDate(to_date))){ return true }else{
		alert('Invalid Date Format');
		return false;
	}
}