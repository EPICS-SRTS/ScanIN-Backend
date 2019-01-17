<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 1/16/19
 * Time: 2:01 PM
 */

include "database.php";

$database = new database();

$unit_ID = $_GET["unit_ID"];
$EID = $_GET["EID"];
$lat = $_GET["lat"];
$long = $_GET["long"];



$database->connect();
$database->recordGPS($unit_ID, $lat, $long);
$database->recordRead($EID);
$database->close();

