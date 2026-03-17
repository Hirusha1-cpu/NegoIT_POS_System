<?php
include_once 'template/m_header.php';
?>
<style>
    .th-style {
        background-color: #467898;
        color: white;
        text-align: left;
        padding: 0 10px;
    }

    .td-style {
        padding: 0 10px;
        background-color: #CCCCCC;
        text-align: left;
    }
</style>
<script>
    function updatePlaceholder() {
        var chequeCheckbox = document.getElementById('cheque');
        var paymentNoInput = document.getElementById('payment_no');
        var typeInput = document.getElementsByName('type')[0];
        var paymentText = document.getElementById('payment_text');

        if (chequeCheckbox.checked) {
            paymentNoInput.placeholder = '';
            typeInput.value = 'cheque';
            paymentText.innerHTML = 'Payment No : ';
        } else {
            paymentNoInput.placeholder = 'Payment Number';
            typeInput.value = '';
            paymentText.innerHTML = 'Payment No : ';
        }
    }
    function confirmFormSubmission(formIdentifier) {
        var confirmation = confirm("Are you sure you want to update this payment?");
        if (confirmation) {
            var form = document.getElementById('form_' + formIdentifier);
            form.submit();
        }
        return false;
    }
</script>
<div class="w3-container" style="margin-top:75px">
    <hr>
    <div class="w3-row">
        <div class="w3-col s3">
        </div>

        <div class="w3-col">
            <table align="center" style="font-size:11pt">
                <tr>
                    <td>
                        <?php
                        if (isset($_REQUEST['message'])) {
                            if ($_REQUEST['re'] == 'success')
                                $color = 'green';
                            else
                                $color = 'red';
                            print '<span style="color:' . $color . '; font-weight:bold;">' . $_REQUEST['message'] . '</span><br /><br />';
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <table align="center">
                <tr>
                    <td></td>
                    <td style="padding-left:10px;">
                        <label for="cheque">Cheque Search?</label>
                        <input type="checkbox" name="cheque" id="cheque" onclick="updatePlaceholder()">
                    </td>
                </tr>
                <tr>
                    <td id="payment_text">Payment No : </td>
                    <td style="padding-left:10px;">
                        <form method="get" action="index.php">
                            <input type="hidden" name="components" value="backend" />
                            <input type="hidden" name="action" value="payment_mgmt" />
                            <input type="hidden" name="type" value="" />
                            <input type="text" style="width:200px;" name="payment_no" id="payment_no"
                                placeholder="Payment Number" />
                            <input type="Submit" value="Search" />
                        </form>
                    </td>
                </tr>
            </table>
            <br>
            <?php if ($payment_found): ?>
                <?php if ($is_cheque): ?>
                    <?php foreach ($payments as $payment): ?>
                        <table align="center" style="font-family: Calibri;">
                            <form method="post" action="index.php?components=backend&action=update_payment"
                                onsubmit="return confirmFormSubmission(<?php echo $payment['id']; ?>);"
                                id="form_<?php echo $payment['id']; ?>">
                                <input type="hidden" name="type" value="cheque" />
                                <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $payment['id']; ?>">
                                <tr>
                                    <th class="th-style" width="200px">Cheque No</th>
                                    <td class="td-style">
                                        <strong><input type="text" name="cheque_no" id="cheque_no" placeholder="Cheque number"
                                                value="<?php echo $payment['cheque_no']; ?>"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Cheque Bank</th>
                                    <td class="td-style">
                                        <strong>
                                            <select name="bank" id="bank">
                                                <option value="">Select Bank</option>
                                                <?php for ($i = 0; $i < count($bank_id); $i++): ?>
                                                    <option value="<?php echo $bank_id[$i]; ?>" <?php echo ($payment['cheque_bank'] == $bank_id[$i]) ? 'selected' : ''; ?>>
                                                        <?php echo $bank_code[$i] . ' - ' . $bank_name[$i]; ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Cheque Branch</th>
                                    <td class="td-style">
                                        <strong><input type="text" name="branch_no" id="branch_no" placeholder="Branch number"
                                                value="<?php echo $payment['cheque_branch']; ?>"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Cheque Date</th>
                                    <td class="td-style"><input type="date" name="cheque_date" id="cheque_date"
                                            value="<?php echo $payment['cheque_date']; ?>"></td>
                                </tr>
                                <!-- <tr>
                                    <th class="th-style" width="200px">Cheque Deposited Date</th>
                                    <td class="td-style"><input type="date" name="chque_deposit_date" id="chque_deposit_date"
                                            value="<?php echo $payment['chque_deposit_date']; ?>"></td>
                                </tr> -->
                                <tr>
                                    <th class="th-style" width="200px">Related Invoice</th>
                                    <td class="td-style"><input type="text" name="invoice_no" id="invoice_no"
                                            placeholder="Invoice number" value="<?php echo $payment['invoice_no']; ?>"></td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Amount</th>
                                    <td class="td-style"><strong><input type="text" name="cheque_amount" id="cheque_amount"
                                                placeholder="Cheque amount" value="<?php echo $payment['amount']; ?>"></strong></td>
                                </tr>
                                <!-- <tr>
                                    <th class="th-style" width="200px">Cheque Deposited Account</th>
                                    <td class="td-style">
                                        <select name="chque_deposit_bank" id="chque_deposit_bank">
                                            <option value="">Select Account</option>
                                            <?php for ($i = 0; $i < count($ac_bank_id); $i++): ?>
                                                <option value="<?php echo $ac_bank_id[$i]; ?>" <?php echo (isset($payment['chque_deposit_bank']) && $payment['chque_deposit_bank'] == $ac_bank_id[$i]) ? 'selected' : ''; ?>>
                                                    <?php echo $ac_bank_id[$i] . ' - ' . $ac_bank_name[$i]; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr> -->
                                <tr>
                                    <th class="th-style" width="200px">Cheque Deposited By</th>
                                    <td class="td-style">
                                        <select name="chque_deposit_by" id="chque_deposit_by">
                                            <option value="">Select Salesman</option>
                                            <?php for ($i = 0; $i < count($sm_id); $i++): ?>
                                                <option value="<?php echo $sm_id[$i]; ?>" <?php echo (isset($payment['chque_deposit_by']) && $payment['chque_deposit_by'] == $sm_id[$i]) ? 'selected' : ''; ?>>
                                                    <?php echo $sm_name[$i]; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Salesman</th>
                                    <td class="td-style">
                                        <select name="salesman" id="salesman">
                                            <option value="">Select Salesman</option>
                                            <?php for ($i = 0; $i < count($sm_id); $i++): ?>
                                                <option value="<?php echo $sm_id[$i]; ?>" <?php echo ($payment['salesman'] == $sm_id[$i]) ? 'selected' : ''; ?>>
                                                    <?php echo $sm_name[$i]; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Customer</th>
                                    <td class="td-style"><?php echo $payment['customer']; ?></td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Cheque Store</th>
                                    <td class="td-style">
                                        <select name="store" id="store">
                                            <option value="">Select Store</option>
                                            <?php for ($i = 0; $i < count($st_id); $i++): ?>
                                                <option value="<?php echo $st_id[$i]; ?>" <?php echo ($payment['store'] == $st_id[$i]) ? 'selected' : ''; ?>>
                                                    <?php echo $st_name[$i]; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Cheque Status</th>
                                    <td class="td-style"><?php echo $payment['status']; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="submit" value="Update" style="width:100%"></td>
                                </tr>
                            </form>
                        </table>
                        <br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                        <table align="center" style="font-family: Calibri;">
                            <form method="post" action="index.php?components=backend&action=update_payment"
                                onsubmit="return confirmFormSubmission(<?php echo $payment['id']; ?>);"
                                id="form_<?php echo $payment['id']; ?>">
                                <input type="hidden" name="type" value="" />
                                <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>" />
                                <tr>
                                    <th class="th-style" width="200px">Payment No</th>
                                    <td class="td-style">
                                        <strong><input type="text" name="payment_id" id="payment_id" placeholder="Payment number"
                                                value="<?php echo $payment['id']; ?>" disabled></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Payment Date</th>
                                    <td class="td-style"><strong><input type="date" name="date" id="date"
                                                value="<?php echo $payment['date']; ?>"></strong></td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Related Invoice</th>
                                    <td class="td-style">
                                        <strong><input type="text" name="invoice_no" id="invoice_no" placeholder="Invoice number"
                                                value="<?php echo $payment['invoice_no']; ?>"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Amount</th>
                                    <td class="td-style"><strong><input type="text" name="payment_amount" id="payment_amount"
                                                placeholder="Payment amount" value="<?php echo $payment['amount']; ?>"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Salesman</th>
                                    <td class="td-style">
                                        <select name="salesman" id="salesman">
                                            <option value="">Select Salesman</option>
                                            <?php for ($i = 0; $i < count($sm_id); $i++): ?>
                                                <option value="<?php echo $sm_id[$i]; ?>" <?php echo ($payment['salesman'] == $sm_id[$i]) ? 'selected' : ''; ?>>
                                                    <?php echo $sm_name[$i]; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Customer</th>
                                    <td class="td-style"><strong><?php echo $payment['customer']; ?></strong></td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Payment Store</th>
                                    <td class="td-style">
                                        <select name="store" id="store">
                                            <option value="">Select Store</option>
                                            <?php for ($i = 0; $i < count($st_id); $i++): ?>
                                                <option value="<?php echo $st_id[$i]; ?>" <?php echo ($payment['store'] == $st_id[$i]) ? 'selected' : ''; ?>>
                                                    <?php echo $st_name[$i]; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Payment Method</th>
                                    <td class="td-style">
                                        <strong>
                                            <select name="payment_method" id="payment_method">
                                                <option value="1" <?php echo ($payment['payment_method'] == '1') ? 'selected' : ''; ?>>Cash</option>
                                                <option value="3" <?php echo ($payment['payment_method'] == '3') ? 'selected' : ''; ?>>Bank</option>
                                                <?php if (isset($payment) && isset($payment['payment_method']) && $payment['payment_method'] == '4') { ?>
                                                    <option value="4" <?php echo ($payment['payment_method'] == '4') ? 'selected' : ''; ?>>Card</option>
                                                <?php } ?>
                                            </select>
                                        </strong>
                                    </td>
                                </tr>
                                <!-- <tr id="bank_details_row"
                                    style="display: <?php echo ($payment['payment_method'] == '3' || $payment['payment_method'] == '4') ? 'table-row' : 'none'; ?>">
                                    <th class="th-style" width="200px">Account Details</th>
                                    <td class="td-style">
                                        <select name="bank_tr" id="bank_tr">
                                            <option value="">Select Account</option>
                                            <?php for ($i = 0; $i < count($ac_bank_id); $i++): ?>
                                                <option value="<?php echo $ac_bank_id[$i]; ?>" <?php echo (isset($payment['bank_tr']) && $payment['bank_tr'] == $ac_bank_id[$i]) ? 'selected' : ''; ?>>
                                                    <?php echo $ac_bank_id[$i] . ' - ' . $ac_bank_name[$i]; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Payment Status</th>
                                    <td class="td-style"><strong><?php echo $payment['status']; ?></strong></td>
                                </tr> -->
                                <tr id="bank_details_row" style="display: <?php
                                if (
                                    isset($payment) && is_array($payment) &&
                                    isset($payment['payment_method']) &&
                                    ($payment['payment_method'] == '3' || $payment['payment_method'] == '4')
                                ) {
                                    echo 'table-row';
                                } else {
                                    echo 'none';
                                }
                                ?>">
                                    <th class="th-style" width="200px">Account Details</th>
                                    <td class="td-style">
                                        <select name="bank_tr" id="bank_tr">
                                            <option value="">Select Account</option>
                                            <?php
                                            // Ensure $ac_bank_id and $ac_bank_name are defined arrays
                                            if (
                                                isset($ac_bank_id) && is_array($ac_bank_id) &&
                                                isset($ac_bank_name) && is_array($ac_bank_name)
                                            ):
                                                for ($i = 0; $i < count($ac_bank_id); $i++):
                                                    $selected = (isset($payment) && is_array($payment) &&
                                                        isset($payment['bank_tr']) &&
                                                        $payment['bank_tr'] == $ac_bank_id[$i])
                                                        ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo $ac_bank_id[$i]; ?>" <?php echo $selected; ?>>
                                                        <?php echo $ac_bank_id[$i] . ' - ' . $ac_bank_name[$i]; ?>
                                                    </option>
                                                    <?php
                                                endfor;
                                            endif;
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="th-style" width="200px">Payment Status</th>
                                    <td class="td-style">
                                        <strong>
                                            <?php echo (isset($payment) && is_array($payment) && isset($payment['status']))
                                                ? $payment['status'] : ''; ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2"><input type="submit" value="Update" style="width:100%"></td>
                                </tr>
                            </form>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php else: ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
<hr>
<br />
<?php
include_once 'template/m_footer.php';
?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var paymentMethodSelect = document.getElementById("payment_method");
        var bankDetailsRow = document.getElementById("bank_details_row");

        // Add change event listener to the payment method select element
        paymentMethodSelect.addEventListener("change", function () {
            // If "Bank" is selected, show the bank details row, otherwise hide it
            bankDetailsRow.style.display = (this.value === '3' || this.value === '4') ? 'table-row' : 'none';
        });

        // Trigger the change event initially to show/hide the bank details row based on the initial selected value
        paymentMethodSelect.dispatchEvent(new Event('change'));
    });
</script>