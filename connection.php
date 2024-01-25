<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "song_of_the_day";
// $dbhost = "sql112.byetcluster.com";
// $dbuser = "if0_35840812";
// $dbpass = "abdisakam1234";
// $dbname = "if0_35840812_song";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";