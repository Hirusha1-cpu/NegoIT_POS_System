<?php
// Use your InfinityFree database details from the screenshot
$conn  = mysqli_connect('sql211.infinityfree.com', 'if0_41411582', 'PWGE8UL94pm9JzC', 'if0_41411582_negoit_db');
$conn2 = mysqli_connect('sql211.infinityfree.com', 'if0_41411582', 'PWGE8UL94pm9JzC', 'if0_41411582_negoit_db');

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
mysqli_set_charset($conn2, 'utf8mb4');
?>