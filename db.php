<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "stock_sales_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>