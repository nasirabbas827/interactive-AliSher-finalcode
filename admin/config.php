<?php
session_start();

// Database configuration
$host = "localhost";
$username = "root";  
$password = "";      
$database = "entrepreneur_portal_ali";

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>