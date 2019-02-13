<?php
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 2/13/2019
 * Time: 1:53 AM
 */

$sUserLogin = "info@scaninsystem.com";
$sUserPassword = "Khalifa@1764";
include __DIR__.'/system/autoload.php';
\Aurora\System\Api::Init();
$aData = \Aurora\System\Api::GetModuleDecorator('Core')->Login($sUserLogin, $sUserPassword);
if (isset($aData['AuthToken']))
{
    $sAuthToken = $aData['AuthToken'];
    setcookie('AuthToken', $sAuthToken, time()+3600, "/");
    \Aurora\System\Api::Location('https://admin.scaninsystem.com/email/');
}
exit();