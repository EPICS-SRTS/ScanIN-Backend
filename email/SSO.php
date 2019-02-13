<?php
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 2/13/2019
 * Time: 1:53 AM
 */

$sEmail="info@scaninsystem.com";
$sPassword="Khalifa@1764";
$sLogin=$sEmail;
include_once './system/autoload.php';
\Aurora\System\Api::Init(true);
header('Location: http://admin.scaninsystem.com/email/?sso&hash='.AuroraSystemApi::GenerateSsoToken($sEmail, $sPassword, $sLogin));