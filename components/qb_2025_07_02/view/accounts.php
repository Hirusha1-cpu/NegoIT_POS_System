<?php
  include_once 'template/header.php';
?>

<style>
	table{
		font-family:Calibri;
	}
	.tbl-header{
		font-family:Calibri;
		color:maroon;
		font-weight:bold;
		background:#EEEEEE;
		min-width: 1300px;
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
		min-width: 1300px;
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
          if(!$out){
            $color='red';
            print '<span style="color:'.$color.'; font-weight:bold;">'.$message.'</span>';
          }
        ?>
      </td>
    </tr>
  </table>
  <!--// Notifications -->

  <?php if($out){ ?>
    <!-- Accounts Balances -->
    <div>
      <table align="center" border="0" class="styled-table">
        <thead>
          <tr>
            <td colspan="6" style="color: black; background: #dddddd;" class="td-style">
              <strong style="padding-left: 10px">QuickBooks Accounts Balances</strong>
            </td>
          </tr>
          <tr>
            <th width="20px" align="left">#</th>
            <th width="120px" align="left">Account Name</th>
            <th width="120px" align="left">Account Type</th>
            <th width="120px" align="left">Account Sub Type</th>
            <th width="100px" align="left">Active</th>
            <th width="120px" align="right">Current Balance</th>
          </tr>
        </thead>
        <tbody>
            <?php
            $accountCount = count($sortedAccounts);
            $totalCurrentBalance = 0; // Use this for total calculation

            if ($accountCount > 0) {
                for ($i = 0; $i < $accountCount; $i++) {
                    $account = $sortedAccounts[$i];
                    $currentBalance = isset($account['current_balance']) ? $account['current_balance'] : 0;
                    $balance = isset($account['balance']) ? $account['balance'] : 0;
                    // Add to total calculations
                    $totalCurrentBalance += $currentBalance;
                    ?>
                    <tr>
                        <td><?php print sprintf('%02d', ($i + 1)); ?></td>
                        <td align="left"><a href="index.php?components=qb&action=general_ledger&id=<?php echo $account['id']; ?>" target="_blank"><?php echo htmlspecialchars($account['name'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                        <td align="left"><?php echo htmlspecialchars($account['account_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td align="left"><?php echo htmlspecialchars($account['account_sub_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td align="left"><?php echo htmlspecialchars($account['active'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td align="right"><?php echo htmlspecialchars(number_format($account['current_balance'], $decimal), ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="6" style="font-weight: bold;" id="total" align="right"><?php echo htmlspecialchars(number_format($totalCurrentBalance, $decimal), ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php
            } else {
                echo '<tr><td colspan="6" align="center">No accounts available</td></tr>';
            }
            ?>
        </tbody>
      </table>
    </div>
    <!--/ Accounts Balances  -->

  <?php } ?>

<?php
  include_once 'template/footer.php';
?>