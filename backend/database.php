<?php
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 1/19/2019
 * Time: 5:47 PM
 */

namespace SRTS\Admin;


class database
{
    private $servername = "localhost";
    private $username = "scanin_remote";
    private $password = "A=;fWPa~P3ps";
    private $dbname = "scanin_SRTS";
    public $conn;

    function connect()
    {
        $this->conn = new mysqli($this->servername, $$this->username, $$this->password, $$this->dbname);
    }

    function close()
    {
        $this->conn->close();
    }
}