<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 2/8/19
 * Time: 2:55 PM
 */


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://admin.scaninsystem.com/WHMCS/loginValidator.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "username=" . "ka7640" . "&password=" . "khalifa");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
echo $result;