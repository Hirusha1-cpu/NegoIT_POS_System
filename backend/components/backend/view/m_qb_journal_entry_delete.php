<?php
include_once 'template/m_header.php';
?>
<style>
  .form-table {
    margin: 0 auto;
    font-family: Calibri, sans-serif;
    border-collapse: collapse;
  }

  .form-table th,
  .form-table td {
    padding: 8px 12px;
    text-align: left;
  }

  .form-table th {
    background-color: #467898;
    color: #ffffff;
  }

  .form-table td {
    background-color: #f2f2f2;
  }

  .submit-btn {
    width: 100%;
    padding: 10px;
    background-color: #467898;
    border: none;
    color: white;
    font-size: 1em;
    cursor: pointer;
  }
</style>

<div class="w3-container" style="margin-top:75px">
  <hr>
  <div class="w3-row">
    <div class="w3-col s2"></div>
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

      <table align="center" class="form-table">
        <form method="post" action="index.php?components=backend&action=qb_delete_journal_entry">
          <tr>
            <th colspan="2">Enter QuickBooks IDs</th>
          </tr>
          <tr>
            <td>QB ID 1:</td>
            <td>
              <input type="text" name="qb_id[]" id="qb_id_1" placeholder="Enter QB ID 1" />
              <button type="button" onclick="incrementAndFill(1)">+</button>
            </td>
          </tr>
          <tr>
            <td>QB ID 2:</td>
            <td>
              <input type="text" name="qb_id[]" id="qb_id_2" placeholder="Enter QB ID 2" />
              <button type="button" onclick="incrementAndFill(2)">+</button>
            </td>
          </tr>
          <tr>
            <td>QB ID 3:</td>
            <td>
              <input type="text" name="qb_id[]" id="qb_id_3" placeholder="Enter QB ID 3" />
              <button type="button" onclick="incrementAndFill(3)">+</button>
            </td>
          </tr>
          <tr>
            <td>QB ID 4:</td>
            <td>
              <input type="text" name="qb_id[]" id="qb_id_4" placeholder="Enter QB ID 4" />
              <button type="button" onclick="incrementAndFill(4)">+</button>
            </td>
          </tr>
          <tr>
            <td>QB ID 5:</td>
            <td>
              <input type="text" name="qb_id[]" id="qb_id_5" placeholder="Enter QB ID 5" />
              <!-- No button for the last input -->
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" class="submit-btn" value="Submit" />
            </td>
          </tr>
        </form>
      </table>
    </div>
    <div class="w3-col s2"></div>
  </div>
  <br>
</div>

<hr>
<br />
<script>
  function incrementAndFill(currentIndex) {
    // Get the current input value
    var currentInput = document.getElementById('qb_id_' + currentIndex);
    var nextInput = document.getElementById('qb_id_' + (currentIndex + 1));
    if (!currentInput || !nextInput) return;

    var value = parseInt(currentInput.value, 10);
    if (!isNaN(value)) {
      nextInput.value = value + 1;
      nextInput.focus();
    } else {
      alert('Please enter a valid integer in the current box.');
      currentInput.focus();
    }
  }
</script>
<?php
include_once 'template/m_footer.php';
?>