<?php
//database credentials.
//if the system doesn't work when you first load it in to the server, it's probably because you made a mistake here
//(or you forgot to enter the credentials altogether.)

$servername = "localhost"; //leave this as localhost unless your database is on a different server than the website.
$username = ""; //your PHPMyAdmin username
$password = ""; //your PHPMyAdmin password
$dbname = ""; //the name of the database to interact with.

//opens a connection to the database.
$conn = new mysqli($servername, $username, $password, $dbname);

?>