<?php
include_once 'template/m_header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
  $(document).ready(function () {
    // Disable submit button initially
    $("#submitBtn").prop('disabled', true);

    $("#search-cust").keyup(function () {
      if (document.getElementById('search-cust').value.length > 2) {
        $.ajax({
          type: "POST",
          url: "index.php?components=bill2&action=cust-list",
          data: 'keyword=' + $(this).val(),
          beforeSend: function () {
            $("#search-cust").css("background", "#FFF url(images/LoaderIcon.gif) no-repeat 165px");
          },
          success: function (data) {
            $("#suggesstion-cust").show();
            $("#suggesstion-cust").html(data);
            $("#search-cust").css("background", "#FFF");
          }
        });
      }
    });
  });

  function selectCust(val) {
    $("#search-cust").val(val);
    $("#suggesstion-cust").hide();

    // Show loading state
    $("#submitBtn").prop('disabled', true);
    $("#submitBtn").val('Loading...');
    $("#status-msg").html('<span style="color:blue;">Loading customer data...</span>');

    getCustData('name', val);
  }

  function getCustData($case, $val) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        try {
          var myObj = JSON.parse(xmlhttp.responseText);
          if (myObj.cust_id) {
            document.getElementById('cust').value = myObj.cust_id;
            $("#submitBtn").prop('disabled', false);
            $("#submitBtn").val('Submit');
            $("#status-msg").html('<span style="color:green; font-weight:bold;">✓ Customer Selected</span>');
          } else {
            $("#status-msg").html('<span style="color:red;">Error: Customer ID not found</span>');
            $("#submitBtn").prop('disabled', true);
          }
        } catch (e) {
          $("#status-msg").html('<span style="color:red;">Error: Invalid response from server</span>');
          $("#submitBtn").prop('disabled', true);
        }
      }
    };
    xmlhttp.open("POST", "index.php?components=bill2&action=more_cust", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('case=' + $case + '&val=' + $val);
  }


  function submitForm() {
    cust = document.getElementById('cust').value;
    if (cust == '') {
      $("#status-msg").html('<span style="color:red;">Please select a customer first</span>');
      return;
    }
    gps_x = document.getElementById("gps_x").value = 0;
    gps_y = document.getElementById("gps_y").value = 0;
    window.location.href = 'index.php?components=bill2&action=new_return&cust=' + cust + '&gps_x=' + gps_x + '&gps_y=' + gps_y;
  }

  window.onload = function () {
    document.getElementById("search-cust").focus();
  };
</script>

<style type="text/css">
  select.selected {
    color: gray;
  }

  table {
    font-size: 12pt;
    font-family: Calibri;
  }
</style>

<style type="text/css">
  #cust-list {
    float: left;
    list-style: none;
    margin-top: -3px;
    padding: 0;
    width: 190px;
    position: absolute;
  }

  #cust-list li {
    padding: 10px;
    background: #F8F8F8;
    border-bottom: #bbb9b9 1px solid;
  }

  #cust-list li:hover {
    background: #ece3d2;
    cursor: pointer;
  }

  #search-cust {
    padding: 10px;
    border: #a8d4b1 1px solid;
    border-radius: 4px;
    min-width: 200px;
  }
</style>


<div class="w3-container" style="margin-top:75px">
  <table align="center">
    <tr>
      <td>
        <div id="notifications"></div>
        <?php
        if (isset($_REQUEST['message'])) {
          if ($_REQUEST['re'] == 'success')
            $color = 'green';
          else
            $color = '#DD3333';
          print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
        }
        ?>

      </td>
    </tr>
  </table>

  <hr>

  <div class="w3-row">
    <div class="w3-col s3"></div>
    <div class="w3-col">
      <table width="100%" align="center">
        <tr>
          <td style="vertical-align:top;">
            <form action="index.php?components=bill2&action=new_return" method="get">
              <input type="hidden" id="gps_x" name="gps_x" value="0" />
              <input type="hidden" id="gps_y" name="gps_y" value="0" />
              <input type="hidden" name="cust" id="cust" value="" />

              <div
                style="margin:0 auto; background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px; height:50px; ">
                <table height="100%" style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle;">
                  <tr>
                    <td><strong>Item Return</strong></td>
                  </tr>
                </table>
              </div>

              <table align="center">
                <tr>
                  <td>
                    <table align="center" bgcolor="#E5E5E5" style="border-radius: 15px; padding:25px;">
                      <tr>
                        <td width="50px"></td>
                        <td style="font-size:12pt">Customer</td>
                        <td colspan="2">
                          <input type="text" id="search-cust" placeholder="Customer Name" autocomplete="nope"
                            style="min-width:200px" />
                          <div id="suggesstion-cust"></div>
                        </td>
                        <td width="50px"></td>
                      </tr>
                      <tr>
                        <td colspan="5">
                          <div id="status-msg" style="margin:10px 5px; text-align:center;"></div>
                        </td>
                      </tr>
                      <tr>
                        <td width="50px"></td>
                        <td style="font-size:12pt"></td>
                        <td colspan="2">
                          <!-- <input type="button" value="Submit" style="width:100px; height:50px;" onclick="selectCust()" /> -->
                          <input type="button" value="Submit" style="width:100px; height:50px;"
                            onclick="submitForm()" />
                          <br /><br />
                        </td>
                        <td width="50px"></td>
                      </tr>
                    </table>
                    <br />
                  </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <hr>
  <hr>
  <div class="w3-row">
    <div class="w3-col s3"></div>
    <div class="w3-col" style="vertical-align:top">
      <div id="portrait">
        <form id="searchinv" action="index.php?components=bill2&action=search_return" method="post">
          <table align="center" height="100%">
            <tr>
              <td>
                <input type="text" style="width:100px" name="search1" id="search1" placeholder="Search Return" />
                <input type="Submit" value="Search" />
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
  <hr>
</div>

<?php include_once 'template/m_footer.php'; ?>