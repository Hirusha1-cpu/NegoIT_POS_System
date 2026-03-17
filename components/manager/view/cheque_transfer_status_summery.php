<?php
    include_once 'template/header.php';
    $decimal = getDecimalPlaces(1);
?>
<style>
    tr {
        transition: background-color 0.3s ease;
    }
</style>

<form action="index.php?components=<?php print $components; ?>&action=pending_cheque_transfers" method="get">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr>
            <td colspan="7">
                <br> &nbsp;&nbsp;&nbsp;Cheque Transfer Status Summery
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
            <th class="tb2">Payment No</th>
            <th class="tb2">Cheque No</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2">From</th>
            <th class="tb2">To</th>
            <th class="tb2">Status</th>
        </tr>
        <?php
            for ($i = 0; $i < sizeof($payment_id); $i++) {
                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
                $status_text = '';
                switch($status[$i]){
                    case 0:
                        $status_text = 'Pending';
                    break;
                    case 1:
                        $status_text = 'Accept';
                    break;
                    case 2:
                        $status_text = 'Not Receive';
                    break;
                    case 3:
                        $status_text = 'Success';
                    break;
                    case 4:
                        $status_text = 'In Trans';
                    break;
                    case 5:
                        $status_text = 'Accept In-Trans';
                    break;
                    case 6:
                        $status_text = 'Reject Trans';
                    break;
                    case 7:
                        $status_text = 'Bank Reject';
                    break;
                    case 8:
                        $status_text = 'Cash Receive';
                    break;
                    case 9:
                        $status_text = 'Issue New Cheque';
                    break;
                    case 10:
                        $status_text = 'Modify Cheque';
                    break;
                    case 11:
                        $status_text = 'Return Pending';
                    break;
                    case 12:
                        $status_text = 'Return Accept';
                    break;
                    case 13:
                        $status_text = 'Return Reject';
                    break;
                    default:
                        $status_text = 'Unknown';
                    break;
                }
                print '<tr id="row_' . $trans_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="checkRow" onclick="updateCheckedCount()"/></td>
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $trans_id[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $chq_full_no[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($trans_time[$i])) . '</td>';
                        if($status[$i] == 1 || $status[$i] == 12){
                            print '<td style="padding-right:10px; padding-left:10px">' . $trans_from[$i] . '</td>';
                            print '<td style="padding-right:10px; padding-left:10px"></td>';
                        }else if($status[$i] == 5){
                            print '<td style="padding-right:10px; padding-left:10px">' . $trans_from[$i] . '</td>';
                            print '<td style="padding-right:10px; padding-left:10px"></td>';
                        }else{
                            print '<td style="padding-right:10px; padding-left:10px">' . $trans_from[$i] . '</td>';
                            print '<td style="padding-right:10px; padding-left:10px">' . $trans_to[$i] . '</td>';
                        }
                        print '<td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>
                </tr>';
            }
        ?>
    </table>
</div>


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
            }else{
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