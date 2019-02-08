<?php
session_start();
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 1/19/2019
 * Time: 5:51 PM
 */
include "database.php";

use SRTS\WHMCS\database as Database;

$database = new Database();
$database->connect();
$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT `PASSWORD` FROM `WHMCS_Authentication` WHERE `USERNAME` = '" . $username . "'";
$result = $database->conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $hash = $row["PASSWORD"];
    }
} else {
    $hash = 0;
}


if (password_verify($password, $hash)) {
    $sql = "SELECT * FROM `WHMCS_Authentication` WHERE `USERNAME` = '" . $username . "'";
    $result = $database->conn->query($sql);
    while ($row = $result->fetch_assoc()) {

    }

    $myArr = array(1, "");
} else {
    $myArr = array(0, "Unable to login to ScanIN server with the user: ".$username);
}


echo json_encode($myArr);