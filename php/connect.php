<?php 
function conn(){

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "epita";
    $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
}
                ?>