<?php
require '../db.php';

$sql = "SELECT markread FROM empuk_email WHERE emailID = " . $_POST['msgid'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$markread = $row['markread'];

if (!$markread) {
	$sql = "UPDATE empuk_email SET markread = TRUE WHERE emailID = " . $_POST['msgid'];
	$conn->query($sql);
}
else {
	$sql = "UPDATE empuk_email SET markread = FALSE WHERE emailID = " . $_POST['msgid'];
	$conn->query($sql);
}
$conn->close();

?>