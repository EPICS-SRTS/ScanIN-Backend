<?php
/**
 * Created by PhpStorm.
 * User: khali
 * Date: 1/25/2019
 * Time: 7:18 PM
 */

namespace SRTS\Admin\Generator;


class table
{
    private $query;
    private $conn;
    private $result;

    function query($query)
    {
        $this->query = $query;
    }

    function setDatabase($conn)
    {
        $this->conn = $conn;
    }

    function generate()
    {
        $this->requestQuery();
        $table = "<div class=\"row\">";
        $table = $table . "<div class=\"col-sm-12\">";
        $table = $table . "<div class=\"card-box\">";
        $table = $table . "<div class=\"table-rep-plugin\">";
        $table = $table . "<div class=\"table-responsive\" data-pattern=\"priority-columns\">";
        $table = $table . "<table id=\"tech-companies-1\" class=\"table  table-striped\">";
        $table = $table . $this->generateHeaders();
        $table = $table . $this->generateData();
        $table = $table . "</table>";
        $table = $table . "</div>";
        $table = $table . "</div>";
        $table = $table . "</div>";
        $table = $table . "</div>";
        $table = $table . "</div>";
        echo $table;
    }

    private function requestQuery()
    {
        $this->result = $this->conn->query($this->query);
    }

    private function generateHeaders()
    {
        $headers = "<thead><tr>";
        for ($x = 0; $x <= $this->countHeaders(); $x++) {
            $headers = $headers . "<th>" . $this->result->fetch_field_direct($x)->name . "</th>";
        }
        $headers = $headers . "</tr></thead>";
        return ($headers);
    }

    private function countHeaders()
    {
        return ($this->result->field_count);
    }

    private function generateData()
    {
        $body = "<tbody>";
        foreach ($this->result as $row) {
            $body = $body . "<tr>";

            for ($x = 0; $x <= $this->countHeaders(); $x++) {
                $body = $body . "<td>" . $row[$this->result->fetch_field_direct($x)->name] . "</td>";
            }
            $body = $body . "</tr>";
        }
        $body = $body . "</tbody>";
        return ($body);
    }
}