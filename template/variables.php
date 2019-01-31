<?php
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 1/19/2019
 * Time: 9:51 PM
 */
session_start();
$URL_PATH = "http://scaninsystem.com/admin";
$Brand = "ScanIN";
$copyright = "2019 © ScanIN. - scaninsystem.com";
$logoPATH = $URL_PATH . "/assets/images/eug_logo_draft.png";
$logoDarkPATH = $URL_PATH . "/assets/images/eug_logo_draft_dark.png";
$Email = $_SESSION["Email"];
$First_Name = $_SESSION["First_Name"];
$Last_Name = $_SESSION["Last_Name"];
$Username = $_SESSION["Username"];
$Logged_IN = $_SESSION["Logged_IN"];
