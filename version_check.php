<?php
include('config.php');

echo 'PHP Version: ' . phpversion();
echo '<br>';

$result = $conn->query("SELECT VERSION();");
if ($result) {
  $row = $result->fetch_row();
  echo "Actual DB Version: " . $row[0];  // This should confirm MariaDB or MySQL
} else {
  echo "Query failed: " . $conn->error;
}
?>