<?php
  include_once  'template/m_header.php';
?>
<style>
  .table-login{
		font-size:12pt; 
		font-family:Calibri;
    box-shadow: 0 0 10px rgb(0 0 0 / 10%);
  }
</style>
<!-- Notifications -->
<div class="w3-container" style="margin-top:75px">
    <table align="center">
      <tr>
        <td>
          <div id="notifications"></div>
        </td>
      </tr>
    </table>

    <?php
      if(isset($_REQUEST['message'])){
        if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
        print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
      }
	  ?>
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
          <form method="post" action="index.php?components=authenticate&action=login" onsubmit="return generateLogIn()">
            <input type="hidden" id="token" value="<?php print $token; ?>" />
    	      <input type="hidden" id="onetime_pass" name="onetime_pass" />
            <table align="center" bgcolor="#EEEEEE" width="300px" class="table-login">
              <tr>
                <td height="80px" align="center" style="font-size:14pt">Backend Login</td>
              </tr>
              <tr>
                <td align="center">
                  <input type="text" name="uname" id="uname"/>
                  <br/><br/>
                </td>
              </tr>
              <tr>
                <td align="center">
                  <input type="password" id="passwd"/>
                        <br /><br />
                </td>
              </tr>
              <tr>
                <td align="center">
                  <div id="div_login">
                    <input class="button" style="width:150px; height:50px; font-weight:bold; color:gray" type="submit" value="Sign In"/>
                  </div>
                </td>
              </tr>
              <tr>
                <td height="80px"></td>
              </tr>
            </table>
          </form>
        </div>
    </div>
</div>


<?php
  include_once  'template/m_footer.php';
?>