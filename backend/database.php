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
    private $username = "username";
    private $password = "password";
    private $dbname = "myDB";
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