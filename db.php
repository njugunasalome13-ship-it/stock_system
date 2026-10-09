<?php
$host = "sql308.infinityfree.com";
$user = "if0_43133442";
$password = "bcm5oe0U6ub3zMf";
$dbname = "if0_43133442_stock_sales_db";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Database Connection Failed: " . mysql_connect_error());
}
?>