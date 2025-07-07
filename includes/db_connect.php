<?php
$hostname = "127.0.0.1";
$database = "projectDb";
$username = "root";
$password = "";
$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die(json_encode(["result" => "error", "message" => "Connection failed: " . mysqli_connect_error()]));
}
?>