<?php
include_once 'template/header.php';
$decimal = getDecimalPlaces(1);
?>
<style>
    .hidden {
        display: none;
    }

    table {
        font-family: Calibri;
    }

    /* Center align radio buttons and dropdowns in the same row */
    .center-align {
        vertical-align: middle;
    }

    /* Style to make dropdowns and input fields uniform */
    select,
    input[type="number"] {
        width: 200px;
        padding: 5px;
        font-size: 12pt;
    }

    /* Align radio buttons and labels together */
    .radio-group label {
        font-size: 12pt;
        vertical-align: middle;
        margin-right: 10px;
    }

    .radio-group input[type="radio"] {
        margin-right: 0px;
        vertical-align: top;
    }

    /* Style the submit button */
    #addpayment input[type="submit"] {
        width: 120px;
        height: 40px;
        background-color: #0158C2;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 12pt;
    }
</style>

<?php
if (isset($_REQUEST['message'])) {
    if ($_REQUEST['re'] == 'success')
        $color = 'green';
    else
        $color = '#DD3333';
    print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
}
?>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center">
    <tr>
        <td valign="top">
            <div
                style="margin:auto, 0, auto, 10px; background-color:#EEEEEF; border-radius: 15px; padding: 10px; height:50px; margin-bottom: 10px;">
                <table height="100%" style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle">
                    <tr>
                        <td><strong>Cash Payment Deposit</strong></td>
                    </tr>
                </table>
            </div>
            <form name="payForm" enctype="multipart/form-data" action="index.php?components=bill2&action=add_cash_payment_deposit" onsubmit="return validateAndConfirmPayment()"
                method="post">
                <table align="center" bgcolor="#E5E5E5" style="border-radius: 15px; padding: 20px; border-spacing: 0 10px;">
                    <tr style="font-size: 14pt;">
                        <td></td>
                        <td>Amount to be settle</td>
                        <td colspan="2" style="text-align: end;">
                            <?php print number_format($amount_to_settle, $decimal); ?>
                        </td>
                        <td></td>
                    </tr>

                    <tr style="font-size: 14pt;">
                        <td></td>
                        <td>In trans amount</td>
                        <td colspan="2" style="text-align: end;">
                            <?php print number_format($payment_in_trans, $decimal); ?>
                        </td>
                        <td></td>
                    </tr>

                    <!-- Radio Buttons for User/Bank -->
                    <tr>
                        <td width="50px"></td>
                        <td class="center-align">Payment Type</td>
                        <td colspan="2">
                            <div class="radio-group">
                                <input type="radio" id="cash_radio" name="payment_type" value="cash" checked>
                                <label for="cash_radio">Cash</label>
                            </div>
                        </td>
                        <td width="50px"></td>
                    </tr>

                    <!-- Radio Buttons for User/Bank -->
                    <tr>
                        <td width="50px"></td>
                        <td class="center-align">Handover</td>
                        <td colspan="2">
                            <div class="radio-group">
                                <input type="radio" id="user_radio" name="source" value="user"
                                    onclick="toggleDropdowns()">
                                <label for="user_radio">User</label>
                                <input type="radio" id="bank_radio" name="source" value="bank"
                                    onclick="toggleDropdowns()">
                                <label for="bank_radio">Bank</label>
                            </div>
                        </td>
                        <td width="50px"></td>
                    </tr>

                    <!-- User Dropdown Row -->
                    <tr id="user_row" class="hidden">
                        <td></td>
                        <td>Select User</td>
                        <td colspan="2">
                            <select id="user" name="user">
                                <option value="">-SELECT USER-</option>
                                <?php for ($i = 0; $i < sizeof($sm_id); $i++) {
                                    print '<option value="' . $sm_id[$i] . '">' . $sm_name[$i] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td></td>
                    </tr>

                    <!-- Bank Dropdown Row -->
                    <tr id="bank_row" class="hidden">
                        <td></td>
                        <td>Select Bank</td>
                        <td colspan="2">
                            <select id="bank" name="bank">
                                <option value="">-SELECT BANK-</option>
                                <?php for ($i = 0; $i < sizeof($ac_bank_id); $i++) {
                                    print '<option value="' . $ac_bank_id[$i] . '">' . $ac_bank_name[$i] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td></td>
                    </tr>

                    <!-- Amount Input -->
                    <tr>
                        <td></td>
                        <td >Amount:</td>
                        <td>
                            <input type="number" id="amount" name="amount" step="0.01" min="0" required>
                        </td>
                        <td></td>
                    </tr>

                    <!-- Slip-->
                    <tr id="slip_row" class="hidden">
                        <td></td>
                        <td>Slip (jpg/jpeg):</td>
                        <td>
                            <input type="file" id="fileToUpload" name="fileToUpload">
                        </td>
                        <td></td>
                    </tr>

                    <!-- Note -->
                    <tr>
                        <td></td>
                        <td>Note:</td>
                        <td>
                            <textarea id="note" name="note" rows="3" style="width:100%"></textarea>
                        </td>
                        <td></td>
                    </tr>

                    <!-- Submit Button -->
                    <tr>
                        <td></td>
                        <td></td>
                        <td colspan="3" height="10px">
                            <div id="addpayment">
                                <input type="submit" value="Add Payment" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" height="10px">
                        </td>
                    </tr>
                </table>
            </form>
            <br />
        </td>
    </tr>
</table>
<div style="width: 20%; margin: 5px auto; font-size: 16px; text-align: center;">
    <p><i>Note: The total amount to be settled is calculated by subtracting the total amount deposited or handed over to the company (excluding rejected or canceled payments) from the total payment you have collected.</i></p>
</div>

<script>
    function validatePayment() {
        // Validate payment type (Cash or Cheque)
        const cashRadio = document.getElementById('cash_radio');
        const chequeRadio = document.getElementById('cheque_radio');
        if (!cashRadio.checked && !chequeRadio.checked) {
            alert('Please select a payment type: Cash or Cheque.');
            return false;
        }

        // Validate source (User or Bank) and corresponding dropdown
        const userRadio = document.getElementById('user_radio');
        const bankRadio = document.getElementById('bank_radio');
        const userSelect = document.getElementById('user');
        const bankSelect = document.getElementById('bank');

        if (!userRadio.checked && !bankRadio.checked) {
            alert('Please select a handover source: User or Bank.');
            return false;
        }

        if (userRadio.checked && userSelect.value === "") {
            alert('Please select a user.');
            return false;
        }

        if (bankRadio.checked && bankSelect.value === "") {
            alert('Please select a bank.');
            return false;
        }

        // Validate amount (must be positive and greater than zero)
        const amount = document.getElementById('amount').value;
        if (amount === "" || parseFloat(amount) <= 0) {
            alert('Please enter a valid amount greater than zero.');
            return false;
        }

        // If all validations pass, submit the form
        return true;
    }
    function toggleDropdowns() {
        const userRow = document.getElementById('user_row');
        const bankRow = document.getElementById('bank_row');
        const slipRow = document.getElementById('slip_row');
        const userSelect = document.getElementById('user');
        const bankSelect = document.getElementById('bank');

        if (document.getElementById('user_radio').checked) {
            userRow.classList.remove('hidden'); // Show User row
            bankRow.classList.add('hidden');    // Hide Bank row
            slipRow.classList.add('hidden');    // Hide Slip row
            bankSelect.value = "";
        } else if (document.getElementById('bank_radio').checked) {
            bankRow.classList.remove('hidden'); // Show Bank row
            slipRow.classList.remove('hidden'); // Show Slip row
            userRow.classList.add('hidden');    // Hide User row
            userSelect.value = "";
        }
    }

    function validateAndConfirmPayment() {
        // First, validate the form inputs
        if (!validatePayment()) {
            return false; // If validation fails, prevent form submission
        }

        // If validation passes, show the confirmation dialog
        return confirm("Are you sure you want to submit this payment?");
    }
</script>

<?php
include_once 'template/footer.php';
?>