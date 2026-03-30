<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<form method="get" action="index.php">
	<input type="hidden" name="components" value="report" />
	<input type="hidden" name="action" value="sub" />
	<input type="hidden" name="report_type" value="<?php print $_GET['report_type']; ?>" />
		<table align="center" style="font-size:12pt; font-family:Calibri" cellspacing="0" bgcolor="#F0F0F0"><tr><td>
			<select id="report_type" name="report_type" onchange="window.location = 'index.php?components=report&action=sub&report_type='+this.value">
				<option value="">---SELECT A REPORT TYPE--</option>
				<option value="newcust_salesman" <?php if($_GET['report_type']=='newcust_salesman') print 'selected="selected"'; else print ''; ?> >New Customers by Salesman</option>
				<option value="itembysalesman" <?php if($_GET['report_type']=='itembysalesman') print 'selected="selected"'; else print ''; ?> >Item Sale By Salesman</option>
				<option value="useraudit" <?php if($_GET['report_type']=='useraudit') print 'selected="selected"'; else print ''; ?> >User Audit</option>
				<option value="transaudit" <?php if($_GET['report_type']=='transaudit') print 'selected="selected"'; else print ''; ?> >Transfer Audit</option>
				<option value="crlimitaudit" <?php if($_GET['report_type']=='crlimitaudit') print 'selected="selected"'; else print ''; ?> >Credit Limit Audit</option>
				<option value="editqtyaudit" <?php if($_GET['report_type']=='editqtyaudit') print 'selected="selected"'; else print ''; ?> >Edit Qty Audit</option>
				<option value="loginaudit" <?php if($_GET['report_type']=='loginaudit') print 'selected="selected"'; else print ''; ?> >Login Audit</option>
				<option value="billeditaudit" <?php if($_GET['report_type']=='billeditaudit') print 'selected="selected"'; else print ''; ?> >Bill Edit Audit</option>
				<option value="payeditaudit" <?php if($_GET['report_type']=='payeditaudit') print 'selected="selected"'; else print ''; ?> >Pay Edit Audit</option>
			</select>
		</td>
		
		<?php 
			if($_GET['report_type']=='newcust_salesman')include_once  'components/report/view/tpl/newcustbysalesman.php';
			if($_GET['report_type']=='itembysalesman')include_once  'components/report/view/tpl/itembysalesman.php';
			if($_GET['report_type']=='useraudit')include_once  'components/report/view/tpl/useraudit.php';
			if($_GET['report_type']=='transaudit')include_once  'components/report/view/tpl/transaudit.php';
			if($_GET['report_type']=='crlimitaudit')include_once  'components/report/view/tpl/crlimitaudit.php';
			if($_GET['report_type']=='editqtyaudit')include_once  'components/report/view/tpl/editqtyaudit.php';
			if($_GET['report_type']=='loginaudit')include_once  'components/report/view/tpl/loginaudit.php';
			if($_GET['report_type']=='billeditaudit')include_once  'components/report/view/tpl/billeditaudit.php';
			if($_GET['report_type']=='payeditaudit')include_once  'components/report/view/tpl/payeditaudit.php';
		?>
		</tr></table>
		<br>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
