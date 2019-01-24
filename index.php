<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 1/23/19
 * Time: 2:14 PM
 */

session_start();

if ($_SESSION["active"]) {
    header('Location: home.php');
} else {
    header('Location: login.php');
}