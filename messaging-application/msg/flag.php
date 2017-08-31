<?php
require '../db.php';

$sql = "SELECT flag FROM empuk_email WHERE emailID = " . $_POST['msgid'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$flag = $row['flag'];

if (!$flag) {
	$sql = "UPDATE empuk_email SET flag = TRUE WHERE emailID = " . $_POST['msgid'];
	$q = $conn->query($sql);
	if ($q)
		echo "Message flagged.";
	else
		echo $conn->error;
}
else {
	$sql = "UPDATE empuk_email SET flag = FALSE WHERE emailID = " . $_POST['msgid'];
	$q = $conn->query($sql);
	if ($q)
		echo "Message unflagged.";
	else
		echo $conn->error;
}
$conn->close();

?>