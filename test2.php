<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 2/8/19
 * Time: 2:55 PM
 */


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://admin.scaninsystem.com/WHMCS/loginValidator.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "username=" . $params['serverusername'] . "&password=" . $params['serverpassword']);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
$obj = json_decode($result);
$success = $obj[0];
$errorMsg = $obj[1];

echo $success;
echo '<br>';
echo $errorMsg;