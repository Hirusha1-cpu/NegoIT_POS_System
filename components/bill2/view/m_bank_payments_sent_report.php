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
                        <div
                            style="margin:auto, 0, auto, 10px; background-color:#EEEEEF; border-radius: 15px; padding: 10px; height:50px; margin-bottom: 10px;">
                            <table height="100%"
                                style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle">
                                <tr>
                                    <td><strong>Bank Payment Sent</strong></td>
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
                                            <td>Bank</td>
                                            <td colspan="2">
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
                                <td colspan="12" style="color: black; background: #dddddd;" class="td-style">
                                    <strong style="padding-left: 10px">Note: This report shows bank transfers sent by
                                        you.
                                    </strong>
                                </td>
                            </tr>
                            <tr>
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
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < sizeof($payment_id); $i++) {
                                $delete_btn = $invoice_link = '';
                                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>';
                                if ($invoice_no[$i] !== '0' && $invoice_no[$i] !== 0 && $invoice_no[$i] !== '') {
                                    $invoice_link = '<a target="_blank" href="index.php?components=billing&action=finish_bill&id=' . $invoice_no[$i] . '">' . str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>';
                                }
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
                                            <td style="padding-right:10px; padding-left:10px">' . $invoice_link . '</td>
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