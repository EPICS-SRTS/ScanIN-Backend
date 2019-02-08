<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 2/8/19
 * Time: 2:55 PM
 */

$url = 'http://admin.scaninsystem.com/backend/loginValidator.php';
$data = array('email' => 'lee2043@purdue.edu', 'password' => 'eugene123');
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    )
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
var_dump($result);