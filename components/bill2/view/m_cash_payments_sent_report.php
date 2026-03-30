<?php
include_once 'template/m_header.php';
$decimal = getDecimalPlaces(1);
?>

<style>
    table {
        font-family: Calibri;
    }

    .wrap {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
    }

    .table-responsive {
        overflow-x: auto;
        white-space: nowrap;
    }
</style>

<script type="text/javascript">
    function filter() {
        var type = '';  // Placeholder if 'type' is not in use
        var source = document.getElementById('source').value;
        var user = document.getElementById('user').value;
        var bank = document.getElementById('bank').value;
        var status = document.getElementById('status').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        // Construct URL with all filter parameters
        var url = 'index.php?components=<?php echo $components; ?>&action=cash_sent_report'
            + '&type=' + type
            + '&source=' + source
            + '&user=' + user
            + '&bank=' + bank
            + '&status=' + status
            + '&datefrom=' + datefrom
            + '&dateto=' + dateto;

        // Trigger page reload with updated filters in the URL
        window.location = url;
    }

    function toggleDropdowns() {
        var userSelect = document.getElementById("user");
        var bankSelect = document.getElementById("bank");

        // Preliminary step: If both have values, clear both so only one can be re-selected
        if (userSelect.value !== "" && bankSelect.value !== "") {
            userSelect.value = "";
            bankSelect.value = "";
            alert("Only one filter (User or Bank) can be selected. Both have been reset.");
        }

        // Apply the final enable/disable logic based on the current selections
        if (userSelect.value !== "") {
            bankSelect.value = ""; // Clear Bank if User is selected
            bankSelect.disabled = true; // Disable Bank
        } else {
            bankSelect.disabled = false; // Enable Bank if no User is selected
        }

        if (bankSelect.value !== "") {
            userSelect.value = ""; // Clear User if Bank is selected
            userSelect.disabled = true; // Disable User
        } else {
            userSelect.disabled = false; // Enable User if no Bank is selected
        }

        // Call filter function to apply changes
        filter();
    }

    function deletePayment(paymentId, paymentType) {
        if (confirm("Are you sure you want to delete this payment?")) {
            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=delete_payment_transfer_ajax", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Handle the response from the server
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse JSON response
                    var response = JSON.parse(xhr.responseText);

                    // Check the status and take appropriate action
                    if (response.status === "success") {
                        var row = document.getElementById("row_" + paymentId);
                        if (row) {
                            row.parentNode.removeChild(row); // Remove the row from the table
                        }
                        alert("Payment deleted successfully."); // Show success message
                    } else {
                        alert(response.message || "Error deleting item."); // Show error message if available
                    }
                }
            };

            // Send the payment ID and type to the server
            xhr.send("id=" + encodeURIComponent(paymentId) + "&type=" + encodeURIComponent(paymentType));
        }
    }
</script>

</head>

<?php
if (isset($_REQUEST['message'])) {
    if ($_REQUEST['re'] == 'success')
        $color = 'green';
    else
        $color = '#DD3333';
    print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
}
?>

<div class="w3-container" style="margin-top:75px">
    <table align="center">
        <tr>
            <td>
                <div id="notifications"></div>
            </td>
        </tr>
    </table>
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <!-- Header -->
            <table align="center" cellspacing="0" class="tbl-header" width="100%">
                <tr>
                    <td align="center" style="padding: 10px; font-size: 13pt;">
                        <div style="margin:auto, 0, auto, 10px; background-color:#EEEEEF; border-radius: 15px; padding: 10px; height:50px; margin-bottom: 10px;">
                            <table height="100%"
                                style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle">
                                <tr>
                                    <td><strong>Cash Payment Sent</strong></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <!--/ Header -->

            <table align="center" cellspacing="0" class="tbl-header" width="100%">
                <tr>
                    <td style="vertical-align:top;">
                        <table align="center">
                            <tr>
                                <td>
                                    <table align="center" bgcolor="#E5E5E5" style="border-radius: 15px; padding:15px;">
                                        <tr>
                                            <td width="50px"></td>
                                            <td>Transfer Source</td>
                                            <td colspan="2">
                                                <select id="source" onchange="filter();">
                                                    <option value="">-ALL-</option>
                                                    <option value="1"
                                                    <?php if ((isset($_GET['source'])) && ($_GET['source'] == '1')) {
                                                        $transfer_source = 'User';
                                                        print 'selected="selected"';
                                                    } ?>>USER</option>
                                                    <option value="2" <?php if ((isset($_GET['source'])) && ($_GET['source'] == '2')) {
                                                        $transfer_source = 'Bank';
                                                        print 'selected="selected"';
                                                    } ?>>BANK</option>
                                                </select>
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                        <tr>
                                            <td width="50px"></td>
                                            <td>User</td>
                                            <td colspan="2">
                                                <select id="user" name="user" onchange="toggleDropdowns();">
                                                    <option value="">-ALL-</option>
                                                    <?php for ($i = 0; $i < sizeof($sm_id); $i++) {
                                                        if (isset($_GET['user'])) {
                                                            if ($_GET['user'] == $sm_id[$i]) {
                                                                $select = 'selected="selected"';
                                                                $salesman_report = $sm_name[$i];
                                                            } else
                                                                $select = '';
                                                        } else
                                                            $select = '';
                                                        print '<option value="' . $sm_id[$i] . '" ' . $select . '>' . ucfirst($sm_name[$i]) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                        <tr>
                                            <td width="50px"></td>
                                            <td>Bank</td>
                                            <td colspan="2">
                                                <select id="bank" name="bank" onchange="toggleDropdowns();">
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
                                            <td width="50px"></td>
                                        </tr>
                                        <tr>
                                            <td width="50px"></td>
                                            <td>Status</td>
                                            <td colspan="2">
                                                <select id="status" name="status" onchange="filter();">
                                                    <option value="" <?php if (!isset($_GET['status']) || $_GET['status'] === '')
                                                        echo 'selected="selected"'; ?>>-ALL-
                                                    </option>
                                                    <option value="0" <?php if (isset($_GET['status']) && $_GET['status'] === '0')
                                                        echo 'selected="selected"'; ?>>CANCELED
                                                    </option>
                                                    <option value="1" <?php if (isset($_GET['status']) && $_GET['status'] === '1')
                                                        echo 'selected="selected"'; ?>>PENDING
                                                    </option>
                                                    <option value="2" <?php if (isset($_GET['status']) && $_GET['status'] === '2')
                                                        echo 'selected="selected"'; ?>>ACCEPTED
                                                    </option>
                                                    <option value="3" <?php if (isset($_GET['status']) && $_GET['status'] === '3')
                                                        echo 'selected="selected"'; ?>>REJECTED
                                                    </option>
                                                    <option value="4" <?php if (isset($_GET['status']) && $_GET['status'] === '4')
                                                        echo 'selected="selected"'; ?>>BANKED
                                                    </option>
                                                </select>
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                        <tr>
                                            <td width="50px"></td>
                                            <td>From</td>
                                            <td colspan="2">
                                                <input type="date" id="datefrom" name="datefrom" style="width:130px"
                                                    value="<?php echo isset($fromdate) ? $fromdate : ''; ?>"
                                                    onchange="filter();" />
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                        <tr>
                                            <td width="50px"></td>
                                            <td>To</td>
                                            <td colspan="2">
                                                <input type="date" id="dateto" name="dateto" style="width:130px"
                                                    value="<?php echo isset($todate) ? $todate : ''; ?>"
                                                    onchange="filter();" />
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="wrap">
                <div class="table-responsive">
                    <table align="center" border="0" class="styled-table" width="100%">
                        <thead>
                            <tr>
                                <td colspan="10" style="color: black; background: #dddddd;" class="td-style">
                                    <strong style="padding-left: 10px">Note: This report shows cash transfers sent by you.</strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="tb2" width="60px">#</th>
                                <th class="tb2" width="80px">Source</th>
                                <th class="tb2">Amount</th>
                                <th class="tb2">Image</th>
                                <th class="tb2">Transfer To</th>
                                <th class="tb2">Transfer Date</th>
                                <th class="tb2" width="250px">Note</th>
                                <th class="tb2">Status</th>
                                <th class="tb2" width="80px" style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < sizeof($payment_id); $i++) {
                                $type = $handover = $status = $delete_btn = $image_link = '';
                                $user_or_bank_name = $payment_received[$i];
                                if($payment_status[$i] == 1){
                                    $delete_btn = '<button onclick="deletePayment(' . $payment_id[$i] . ','. $payment_type[$i] .')">Delete</button>';
                                }
                                if($payment_image[$i] == 1){
                                    $image_link = '<a target="_blank" href="../../../../images/customerdata/'.$systemid.'/payment_deposit/'.str_pad($payment_id[$i], 10, "0", STR_PAD_LEFT).'.jpg">View</a>';
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
                                            <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                                            <td style="padding-right:10px; padding-left:10px">' . $handover . '</td>
                                            <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                                            <td align="center" style="padding-right:10px; padding-left:10px">'.$image_link.'</td>
                                            <td style="padding-right:10px; padding-left:10px">' . $payment_received[$i] . '</td>
                                            <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                                            <td style="padding-right:10px; padding-left:10px">' . $payment_note[$i] . '</td>
                                            <td style="padding-right:10px; padding-left:10px">' . $status . '</td>
                                            <td style="padding-right:10px; padding-left:10px; style="text-align:center;">' . $delete_btn . '</td>
                                    </tr>';
                            }
                            print '<tr style="height: 35px">
                                        <td colspan="8" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
                                    </tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>

<?php
include_once 'template/m_footer.php';
?>