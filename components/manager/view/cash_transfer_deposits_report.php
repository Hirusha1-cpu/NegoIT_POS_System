<?php
include_once 'template/header.php';
$decimal = getDecimalPlaces(1);
$transfer_source = $user_or_bank_name = $bank_account = 'ALL';
?>

<script type="text/javascript">
    function filter() {
        var type = '';  // Placeholder if 'type' is not in use
        var source = document.getElementById('source').value;
        var user = document.getElementById('user').value;
        var reviewedByElement = document.getElementById('reviewed_by');
        var reviewed_by = reviewedByElement ? reviewedByElement.value : null;
        var bank = document.getElementById('bank').value;
        var status = document.getElementById('status').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        // Construct URL with all filter parameters
        var url = 'index.php?components=<?php echo $components; ?>&action=cash_transfer_deposits_report'
            + '&source=' + source
            + '&user=' + user
            + '&reviewed_by=' + reviewed_by
            + '&bank=' + bank
            + '&status=' + status
            + '&datefrom=' + datefrom
            + '&dateto=' + dateto;

        // Trigger page reload with updated filters in the URL
        window.location = url;
    }
</script>

<style>
    tr {
        transition: background-color 0.3s ease;
    }
</style>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php?components=<?php print $components; ?>&action=cash_transfer_deposits_report" method="post">
    <table border="0" width="1200px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr style="height: 40px">
            <td align="center">
                <strong>Transfer Source</strong>&nbsp;&nbsp;&nbsp;
                <select id="source" onchange="filter();">
                    <option value="">-ALL-</option>
                    <option value="1" <?php if ((isset($_GET['source'])) && ($_GET['source'] == '1')) {
                        $transfer_source = 'User';
                        print 'selected="selected"';
                    } ?>>USER</option>
                    <option value="2" <?php if ((isset($_GET['source'])) && ($_GET['source'] == '2')) {
                        $transfer_source = 'Bank';
                        print 'selected="selected"';
                    } ?>>BANK</option>
                </select>
            </td>
            <td align="center">
                <strong>Salesman</strong>&nbsp;&nbsp;&nbsp;
                <select id="user" name="user" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($sm_id); $i++) {
                        if (isset($_GET['user'])) {
                            if ($_GET['user'] == $sm_id[$i]) {
                                $select = 'selected="selected"';
                            } else
                                $select = '';
                        } else
                            $select = '';
                        print '<option value="' . $sm_id[$i] . '" ' . $select . '>' . ucfirst($sm_name[$i]) . '</option>';
                    }
                    ?>
                </select>
            </td>
            <?php if ($_REQUEST['components'] != 'supervisor') { ?>
                <td align="center">
                    <strong>Reviewed By</strong>&nbsp;&nbsp;&nbsp;
                    <select id="reviewed_by" name="reviewed_by" onchange="filter();">
                        <option value="">-ALL-</option>
                        <?php for ($i = 0; $i < sizeof($manage_user_id); $i++) {
                            if (isset($_GET['reviewed_by'])) {
                                if ($_GET['reviewed_by'] == $manage_user_id[$i]) {
                                    $select = 'selected="selected"';
                                } else
                                    $select = '';
                            } else
                                $select = '';
                            print '<option value="' . $manage_user_id[$i] . '" ' . $select . '>' . ucfirst($manage_user_name[$i]) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            <?php } ?>
            <td align="center">
                <strong>Bank</strong>&nbsp;&nbsp;&nbsp;
                <select id="bank" name="bank" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($bnk_id); $i++) {
                        if (isset($_GET['bank'])) {
                            if ($_GET['bank'] == $bnk_id[$i]) {
                                $select = 'selected="selected"';
                                $bank_account = $bnk_name[$i];
                            } else
                                $select = '';
                        } else
                            $select = '';
                        print '<option value="' . $bnk_id[$i] . '" ' . $select . '>' . ucfirst($bnk_name[$i]) . '</option>';
                    }
                    ?>
                </select>
            </td>
            <td align="center">
                <strong>Status</strong>&nbsp;&nbsp;&nbsp;
                <select id="status" name="status" onchange="filter();">
                    <option value="" <?php if (!isset($_GET['status']) || $_GET['status'] === '')
                        echo 'selected="selected"'; ?>>-ALL-</option>
                    <option value="0" <?php if (isset($_GET['status']) && $_GET['status'] === '0')
                        echo 'selected="selected"'; ?>>CANCELED</option>
                    <option value="1" <?php if (isset($_GET['status']) && $_GET['status'] === '1')
                        echo 'selected="selected"'; ?>>PENDING</option>
                    <option value="2" <?php if (isset($_GET['status']) && $_GET['status'] === '2')
                        echo 'selected="selected"'; ?>>ACCEPTED</option>
                    <option value="3" <?php if (isset($_GET['status']) && $_GET['status'] === '3')
                        echo 'selected="selected"'; ?>>REJECTED</option>
                    <option value="4" <?php if (isset($_GET['status']) && $_GET['status'] === '4')
                        echo 'selected="selected"'; ?>>BANKED</option>
                </select>
            </td>
            <td align="center">
                <strong>From</strong>&nbsp;&nbsp;&nbsp;
                <input type="date" id="datefrom" name="datefrom" style="width:130px"
                    value="<?php echo isset($fromdate) ? htmlspecialchars($fromdate) : ''; ?>" onchange="filter();" />
            </td>
            <td align="center">
                <strong>To</strong>&nbsp;&nbsp;&nbsp;
                <input type="date" id="dateto" name="dateto" style="width:130px"
                    value="<?php echo isset($todate) ? htmlspecialchars($todate) : ''; ?>" onchange="filter();" />
            </td>
        </tr>
        <tr>
            <td colspan="7">
                <br> &nbsp;&nbsp;&nbsp;Note: This report shows cash payments collected by salesmen, either transfer to
                user or bank.
                <hr />
            </td>
        </tr>
    </table>
</form>
<br>

<div>
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="14" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2"><span id="checkedCount"></span></th>
            <th class="tb2">#</th>
            <th class="tb2">Trans No</th>
            <th class="tb2">Source</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Image</th>
            <th class="tb2">Placed By</th>
            <th class="tb2">Transfer To</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2" width="100px">Note</th>
            <th class="tb2">Reviewed By</th>
            <th class="tb2">Status</th>
        </tr>
        <?php

        for ($i = 0; $i < sizeof($payment_id); $i++) {
            $type = $handover = $status = $image_link = '';
            $user_or_bank_name = $payment_received[$i];
            if ($payment_image[$i] == 1) {
                $image_link = '<a target="_blank" href="../../../../images/customerdata/' . $systemid . '/payment_deposit/' . str_pad($payment_id[$i], 10, "0", STR_PAD_LEFT) . '.jpg">View</a>';
            }
            if ($payment_type[$i] == 1) {
                $type = 'CASH';
            } else {
                $type = 'CHQUE';
            }
            if ($payment_source[$i] == 1)
                $handover = 'USER';
            else
                $handover = 'BANK';

            switch ($payment_status[$i]) {
                case '0':
                    $status = 'CANCELED';
                    break;
                case '1':
                    $status = 'PENDING';
                    break;
                case '2':
                    $status = 'ACCEPTED';
                    break;
                case '3':
                    $status = 'REJECTED';
                    break;
                case '4':
                    $status = 'BANKED';
                    break;
                default:
                    $status = 'UNKNOWN';
                    break;
            }

            print '<tr id="row_' . $payment_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                    <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="checkRow" onclick="updateCheckedCount()"/></td>
                    <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', $payment_id[$i]) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $handover . '</td>
                    <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                    <td align="center" style="padding-right:10px; padding-left:10px">' . $image_link . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $payment_placed_by[$i] . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $payment_received[$i] . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $payment_note[$i] . '</td>';
            if ($payment_status[$i] == 3) {
                print '<td style="padding-right:10px; padding-left:10px">' . $payment_reject_by[$i] . '</td>';
            } else {
                print '<td style="padding-right:10px; padding-left:10px">' . $payment_reviewed_by[$i] . '</td>';
            }
            print '<td style="padding-right:10px; padding-left:10px">' . $status . '</td>
                </tr>';
        }
        print '<tr style="height: 35px">
                <td colspan="14" align="right" style="padding-right:10px; padding-left:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
            </tr>';
        ?>
    </table>
</div>

<div id="print" style="display:none">
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="10" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2">#</th>
            <th class="tb2">Trans No</th>
            <th class="tb2">Source</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Placed By</th>
            <th class="tb2">Transfer To</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2" width="100px">Note</th>
            <th class="tb2">Reviewed By</th>
            <th class="tb2">Status</th>
        </tr>
        <?php

        for ($i = 0; $i < sizeof($payment_id); $i++) {
            $type = $handover = $status = $delete_btn = '';
            $user_or_bank_name = $payment_received[$i];
            if ($payment_type[$i] == 1) {
                $type = 'CASH';
            } else {
                $type = 'CHQUE';
            }
            if ($payment_source[$i] == 1)
                $handover = 'USER';
            else
                $handover = 'BANK';

            switch ($payment_status[$i]) {
                case '0':
                    $status = 'CANCELED';
                    break;
                case '1':
                    $status = 'PENDING';
                    break;
                case '2':
                    $status = 'ACCEPTED';
                    break;
                case '3':
                    $status = 'REJECTED';
                    break;
                case '4':
                    $status = 'BANKED';
                    break;
                default:
                    $status = 'UNKNOWN';
                    break;
            }

            print '<tr id="row_' . $payment_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                    <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', $payment_id[$i]) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $handover . '</td>
                    <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $payment_placed_by[$i] . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $payment_received[$i] . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $payment_note[$i] . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $payment_reviewed_by[$i] . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $status . '</td>
            </tr>';
        }
        print '<tr style="height: 35px">
                <td colspan="10" align="right" style="padding-right:10px; padding-left:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
            </tr>';
        ?>
    </table>
</div>

<div id="printheader" style="display:none">
    <h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
    <h3 align="center" style="color:#333399; text-decoration:underline">Cash Transfers Report<< /h3>
            <table style="font-size:12pt" border="1" cellspacing="0">
                <tr>
                    <td style="background-color:#C0C0C0; padding-left:10px">
                        &nbsp;&nbsp;Transfer Type&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="background-color:#EEEEEE; padding-left:10px">
                        &nbsp;&nbsp;<?php print $transfer_source; ?>&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#C0C0C0; padding-left:10px">
                        &nbsp;&nbsp;<?php if ((isset($_GET['user']) && $_GET['user'] != '')) {
                            echo 'User';
                        } else if ((isset($_GET['bank']) && $_GET['bank'] != '')) {
                            echo 'Bank';
                        } else {
                            echo 'User/Bank';
                        } ?>&nbsp;&nbsp;
                    </td>
                    <td style="background-color:#EEEEEE; padding-left:10px">
                        &nbsp;&nbsp;<?php
                        if ((!isset($_GET['user']) || $_GET['user'] === '') && (!isset($_GET['bank']) || $_GET['bank'] === '')) {
                            // Show 'All' only if both 'user' and 'bank' are not set or empty
                            print 'ALL';
                        } else {
                            // Otherwise, show $user_or_bank_name if either 'user' or 'bank' has a value
                            print $user_or_bank_name;
                        }
                        ?>&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#C0C0C0; padding-left:10px">
                        &nbsp;&nbsp;Date Range&nbsp;&nbsp;
                    </td>
                    <td style="background-color:#EEEEEE; padding-left:10px">
                        &nbsp;&nbsp;<?php
                        if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
                            echo "FROM: " . htmlspecialchars($_GET['datefrom']) . " - ";
                        }
                        if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
                            echo "TO: " . htmlspecialchars($_GET['dateto']) . "";
                        }
                        ?>&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#C0C0C0; padding-left:10px">
                        &nbsp;&nbsp;Status&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="background-color:#EEEEEE; padding-left:10px">
                        &nbsp;&nbsp;<?php
                        if (!isset($_GET['status']) || $_GET['status'] === '') {
                            echo 'ALL';
                        } else {
                            switch ($_GET['status']) {
                                case '0':
                                    echo 'CANCELED';
                                    break;
                                case '1':
                                    echo 'PENDING';
                                    break;
                                case '2':
                                    echo 'ACCEPTED';
                                    break;
                                case '3':
                                    echo 'REJECTED';
                                    break;
                                case '4':
                                    echo 'BANKED';
                                    break;
                                default:
                                    echo 'UNKNOWN';
                            }
                        }
                        ?>&nbsp;&nbsp;
                    </td>
                </tr>
            </table>
            <br />
            <p>Note: This report shows cash payments collected by salesmen, either transfer to user or bank which need
                to be sent for review to confirm payment</p>
            <br>
</div>

<br>
<table align="center">
    <tr>
        <td align="center">
            <div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
                <a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
                    <span style="text-decoration:none; font-family:Arial; color:navy;">
                        <img src="images/print.png" alt="icon" /><br />Print
                    </span></a>
            </div>
        </td>
    </tr>
</table>
<br />

<script>
    function updateCheckedCount() {
        const checkboxes = document.querySelectorAll(".checkRow");
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const checkedCountDisplay = document.getElementById("checkedCount");
        checkedCountDisplay.textContent = checkedCount > 0 ? checkedCount : ""; // Display "" if no checkboxes are checked

        // Highlight rows for checked checkboxes
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest("tr");
            if (checkbox.checked) {
                row.style.backgroundColor = "#BFED9B"; // Highlight color
            } else {
                row.style.backgroundColor = "#F5F5F5"; // Highlight color
            }
        });
    }

    // Call it initially to set the count on page load
    updateCheckedCount();
</script>

<?php
include_once 'template/footer.php';
?>