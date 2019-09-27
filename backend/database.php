<?php
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 1/19/2019
 * Time: 5:47 PM
 */

namespace SRTS\Admin;


use mysqli;

class database
{
    private $servername = "localhost";
    private $username = "admin_backend";
    private $password = "9EXNKgrUBK";
    private $dbname = "admin_SCAN";
    public $conn;

    function connect()
    {

        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function query($query)
    {
        return ($this->conn->query($query));
    }

    function getClearance($email)
    {
        $result = $this->conn->query("SELECT * FROM Clearance_Level WHERE ID = (SELECT Clearance_Level FROM ScanIN_Users WHERE Email = '$email' LIMIT 1)");
        while ($row = $result->fetch_assoc()) {
            $clearance = array("Dashboard" => $row["Dashboard"], "Self_Member" => $row["Self_Member"], "Members" => $row["Members"], "New_Card" => $row["New_Card"], "Replace_Card" => $row["Replace_Card"], "Lost_Card" => $row["Lost_Card"], "Contact" => $row["Contact"], "Support" => $row["Support"], "Email" => $row["Email"]);
        }
        return ($clearance);
    }

    function close()
    {
        $this->conn->close();
    }

    function TESTconnect()
    {

        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            fwrite(STDERR, "\nConnection failed: " . $this->conn->connect_error . "\n");
            exit(1);
        }else{
            fwrite(STDERR, "success!\n");
        }
    }
}
