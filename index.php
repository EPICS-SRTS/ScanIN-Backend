<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 1/23/19
 * Time: 2:14 PM
 */

session_start();
include "template/variables.php";
if ($_SESSION["Logged_IN"]) {
    header('Location: ' . $URL_PATH . '/home.php');
} else {
    header('Location: ' . $URL_PATH . '/account/login.php');
}