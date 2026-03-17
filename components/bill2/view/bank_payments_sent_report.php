<?php
include_once 'template/header.php';
$bank_account = 'ALL';
$decimal = getDecimalPlaces(1);
?>

<script type="text/javascript">
    function filter() {
        var bank = document.getElementById('bank').value;
        var status = document.getElementById('status').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        // Construct URL with all filter parameters
        var url = 'index.php?components=<?php echo $components; ?>&action=bank_payments_sent_report'
            + '&bank=' + bank
            + '&status=' + status
            + '&datefrom=' + datefrom
            + '&dateto=' + dateto;

        // Trigger page reload with updated filters in the URL
        window.location = url;
    }

    function deletePayment(paymentId, paymentType) {
        if (confirm("Are you sure you want to delete this payment?")) {
            // Get the cell containing the button for the specific row
            var buttonCell = document.querySelector("#row_" + paymentId + " td:last-child");

            // Show loading GIF in place of the Delete button
            if (buttonCell) {
                buttonCell.innerHTML = '<img src="images/loading.gif" style="width:40px" />';
            }

            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=delete_payment_transfer_ajax", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Handle the response from the server
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.status === "success") {
                            // Replace loading GIF with "DELETED" in red text
                            if (buttonCell) {
                                buttonCell.innerHTML = '<span style="color: red; font-weight: bold;">DELETED</span>';
                            }
                            alert("Payment deleted successfully."); // Show success message
                        } else {
                            // Restore Delete button if there is an error
                            if (buttonCell) {
                                buttonCell.innerHTML = '<button onclick="deletePayment(' + paymentId + ', \'' + paymentType + '\')">Delete</button>';
                            }
                            alert(response.message || "Error deleting item.");
                        }
                    } else {
                        // Restore Delete button if the request fails
                        if (buttonCell) {
                            buttonCell.innerHTML = '<button onclick="deletePayment(' + paymentId + ', \'' + paymentType + '\')">Delete</button>';
                        }
                        alert("Request failed. Please try again.");
                    }
                }
            };

            // Send the payment ID and type to the server
            xhr.send("id=" + encodeURIComponent(paymentId) + "&type=" + encodeURIComponent(paymentType));
        }
    }
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php?components=<?php print $components; ?>&action=bank_payments_sent_report" method="post">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr style="height: 40px">
            <td align="center">
                <strong>Bank</strong>&nbsp;&nbsp;&nbsp;
                <select id="bank" name="bank" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($ac_bank_id); $i++) {
                        if (isset($_GET['bank'])) {
                            if ($_GET['bank'] == $ac_bank_id[$i]) {
                                $select = 'selected="selected"';
                                $bank_account = $ac_bank_name[$i];
                            } else
                                $select = '';
                        } else
                            $select = '';
                        print '<option value="' . $ac_bank_id[$i] . '" ' . $select . '>' . ucfirst($ac_bank_name[$i]) . '</option>';
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
                <br> &nbsp;&nbsp;&nbsp;Note: This report shows bank transfers sent by you.
                <hr />
            </td>
        </tr>
    </table>
</form>
<br>

<div>
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="12" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2"></th>
            <th class="tb2">#</th>
            <th class="tb2">Payment No</th>
            <th class="tb2">Customer Name</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Bank Name</th>
            <th class="tb2">Date</th>
            <th class="tb2" style="width: 100px;">Ref</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2">Note</th>
            <th class="tb2">Status</th>
            <th class="tb2" width="80px" style="text-align:center;">Action</th>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($payment_id); $i++) {
            $delete_btn = '';
            $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>';
            if ($payment_status[$i] == 1) {
                $delete_btn = '<button onclick="deletePayment(' . $payment_deposit_id[$i] . ',' . $payment_type[$i] . ')">Delete</button>';
            }
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

            print '<tr id="row_' . $payment_deposit_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox"/></td>
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $cust_name[$i] . '</td>
                        <td align="right" style="padding-right:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $bank_name[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $comment[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_transfer_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_transfer_note[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status . '</td>
                        <td style="padding-right:10px; padding-left:10px; text-align:center;">' . $delete_btn . '</td>
                </tr>';
        }
        print '<tr style="height: 35px">
                    <td colspan="12" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
                </tr>';
        ?>
    </table>
</div>

<div id="print" style="display:none">
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2">#</th>
            <th class="tb2">Payment No</th>
            <th class="tb2">Customer Name</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Bank Name</th>
            <th class="tb2">Date</th>
            <th class="tb2" style="width: 100px;">Ref</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2">Status</th>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($payment_id); $i++) {
            $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>';
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

            print '<tr id="row_' . $payment_deposit_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $cust_name[$i] . '</td>
                        <td align="right" style="padding-right:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $bank_name[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $comment[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_transfer_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status . '</td>
                </tr>';
        }
        print '<tr style="height: 35px">
                    <td colspan="11" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
                </tr>';
        ?>
    </table>
</div>

<div id="printheader" style="display:none">
    <h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
    <h3 align="center" style="color:#333399; text-decoration:underline">Bank Transfers Report</h3>
    <table style="font-size:12pt" border="1" cellspacing="0">
        <tr>
            <td style="background-color:#C0C0C0; padding-left:10px">
                &nbsp;&nbsp;Bank&nbsp;&nbsp;&nbsp;
            </td>
            <td style="background-color:#EEEEEE; padding-left:10px">
                &nbsp;&nbsp;<?php print $bank_account; ?>&nbsp;&nbsp;
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
    <p>Note: This report shows bank transfers sent by you.</p>
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

<?php
include_once 'template/footer.php';
?>