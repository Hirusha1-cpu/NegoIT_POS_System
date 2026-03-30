<?php
if (isset($_SESSION['userkey'])) {
  if ($systemid == 13) $salesCommission = 'Commission: ' . number_format(salesCommission());
  else $salesCommission = '';
} else $salesCommission = '';
?>
<footer class="w3-container <?php print $mailtheme; ?> w3-bottom">
  <table width="100%">
    <tr>
      <td><span><strong>Store</strong> : <?php if (isset($_COOKIE['store_name'])) print $_COOKIE['store_name']; ?> <?php print $apptitle; ?></span></td>
      <td align="right"><?php print $salesCommission; ?></td>
    </tr>
  </table>
</footer>

<script>
  function w3_open() {
    document.getElementById("mySidenav").style.display = "block";
  }

  function w3_close() {
    document.getElementById("mySidenav").style.display = "none";
  }
</script>

<script type="text/javascript">
  var portrait = document.getElementById('portrait').innerHTML;
  var landscape = document.getElementById('landscape').innerHTML;
  if (window.orientation == 0) {
    if (landscape != '') document.getElementById('portrait').innerHTML = landscape;
    document.getElementById('landscape').innerHTML = '';
  } else if (window.orientation == 90) {
    if (portrait != '') document.getElementById('landscape').innerHTML = portrait;
    document.getElementById('portrait').innerHTML = '';
  } else if (window.orientation == 180) {
    if (landscape != '') document.getElementById('portrait').innerHTML = landscape;
    document.getElementById('landscape').innerHTML = '';
  } else if (window.orientation == -90) {
    if (portrait != '') document.getElementById('landscape').innerHTML = portrait;
    document.getElementById('portrait').innerHTML = '';
  }
</script>
<script>
  window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent Chrome 67 and earlier from automatically showing the prompt
    e.preventDefault();
    // Stash the event so it can be triggered later.
    deferredPrompt = e;
  });
</script>
<script src="js/toastr.min.js"></script>
</body>
</html>