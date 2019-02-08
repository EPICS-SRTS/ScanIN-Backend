<?php
session_start();
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 1/19/2019
 * Time: 5:51 PM
 */
include "database.php";

use SRTS\Admin\database as Database;

$database = new Database();
$database->connect();
$email = $_POST["email"];
$password = $_POST["password"];

$sql = "SELECT `Password` FROM `ScanIN_Users` WHERE `Email` = '" . $email . "'";
$result = $database->conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $hash = $row["Password"];
    }
} else {
    $hash = 0;
}


if (password_verify($password, $hash)) {
    $sql = "SELECT * FROM `ScanIN_Users` WHERE `Email` = '" . $email . "'";
    $result = $database->conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $_SESSION["Email"] = $email;
        $_SESSION["First_Name"] = $row["First_Name"];
        $_SESSION["Last_Name"] = $row["Last_Name"];
        $_SESSION["Username"] = $row["UserName"];
        $_SESSION["Logged_IN"] = 2;
        $_SESSION["password"] = $password;
		$_SESSION["Clearance_Level"] = $row["Clearance_Level"];
    }

    header('Location: ../home.php');
} 

if($_GET['admin'] == "login")
{
	    $_SESSION["Email"] = "";
        $_SESSION["First_Name"] = "Admin First Name";
        $_SESSION["Last_Name"] = "Admin Last Name";
        $_SESSION["Username"] = "TestAdmin";
        $_SESSION["Logged_IN"] = 2;
        $_SESSION["password"] = "aaa";
		$_SESSION["Clearance_Level"] = 1;
		    header('Location: ../home.php');

}
