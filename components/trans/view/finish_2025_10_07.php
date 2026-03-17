<?php
                include_once  'template/header.php';
                $paper_size=paper_size(1);
                if($paper_size=='A4'){
                	$page_width=680;
                	$page_height=1070;
                }
                if($paper_size=='A5'){
                	$page_width=480;
                	$page_height=680;
                }
?>

<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>

<form action="index.php?components=trans&action=apend_gtn" method="post" >
<input type="hidden" name="id" value="<?php print $id; ?>" />
<table align="center">
<tr><td>
<!-- ------------------Item List----------------------- -->
<?php print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="'.$page_height.'px" src="components/trans/view/tpl/gtn_print.php?id='.$_GET['id'].'&approve_permission='.$_GET['approve_permission'].'"></iframe>'; ?>


</td><td valign="top" align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="print_bill()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />
				</span></a>
</div>
<br />
<div id="deletegtn" style="background-color:#FF9191; border:medium; border-color:black; width:80px;">
<?php if($trans_delete) { ?>
				<a class="shortcut-button" onclick="deleteGTN(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/cancel.png" alt="icon" /><br />
				</span></a>
<?php }else{
print '<p>The GTN was<br /> placed by </br><strong>'.ucfirst($gtnowner_name).'</strong></p>';
} ?>
</div>
<?php
if($gtnowner_status==3) print '<div id="deletegtn" style="background-color:#FF9191; border:medium; border-color:black; width:80px;"><p>The GTN was<br /> Deleted by </br><strong>'.ucfirst($gtnowner_name).'</strong></p></div>';
if($gtnowner_status==2) print '<div id="deletegtn" style="background-color:#FF9191; border:medium; border-color:black; width:80px;"><p>The GTN was<br /> Rejected by </br><strong>'.ucfirst($gtnremote_name).'</strong></p></div>';
?>
<br />
<?php if($_GET['approve_permission']==1) { ?>
<div id="approvegtn" style="background-color:#CCCCCC; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="approveGTN(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/approve.png" alt="icon" /><br />
				</span></a>
</div>
<?php } ?>
<br /><br />
<?php if($_GET['approve_permission']==1) { ?>
<div id="rejectgtn" style="background-color:#FF9191; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="rejectGTN(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/reject.png" alt="icon" /><br />
				</span></a>
</div>
<?php } if($gtnowner_crossinv){ ?>
<div id="crossgtn" style="background-color:#6699FF; border:medium; border-color:black; width:80px; height:23px; border-radius: 15px; padding-top:4px">
				<a class="shortcut-button" onclick="crossSubmitGTN(<?php print $_GET['id']; ?>)" href="#" style="text-decoration:none; font-family:Arial; color:white;">
					SUBMIT
				</a>
</div>
<?php } ?>
</td></tr>
</table>
</form>

<?php
                include_once  'template/footer.php';
?>