<?php
/**
 * Created by PhpStorm.
 * User: ka7640
 * Date: 1/16/19
 * Time: 1:48 PM
 */

class database
{
    private $servername = "localhost";
    private $username = "SRTS_Remote";
    private $password = "f5AiME9KKjb10ggD";
    private $dbname = "SRTS";
    private $conn;

    function connect()
    {
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
// Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function query($query)
    {
        $sql = $query;
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
            }
        } else {
            echo "0 results";
        }
    }

    function recordRead($EID)
    {
        $result = $this->conn->query("INSERT INTO `Scans`(`EID`) VALUES ('" . $EID . "')");
        return ($result);
    }

    function recordGPS($unit, $Lat, $Long)
    {
        $result = $this->conn->query("INSERT INTO `GPS`(`Unit_ID`, `Latitude`, `Longitude`) VALUES ('" . $unit . "','" . $Lat . "','" . $Long . "')");
        return ($result);
    }

    function close()
    {
        $this->conn->close();

    }
}