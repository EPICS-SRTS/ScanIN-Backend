<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 1/30/19
 * Time: 4:56 PM
 */
include "backend/database.php";

use SRTS\Admin\database as Database;

$database = new Database();


fwrite(STDERR, "\n\n\nStarting System Testing...\n");
fwrite(STDERR, "Attempting to connect to database...");
$database->TESTconnect();
fwrite(STDERR, "Attempting to preform admin user test login...");
$url = 'http://admin.scaninsystem.com/backend/loginValidator.php';
$data = array('email' => 'test@scaninsystem.com', 'password' => 'test123');
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


if (true) {
    fwrite(STDERR, "\n\n\nSuccessfully connected to the database\n\n");
    exit(0); // A response code other than 0 is a failure
}