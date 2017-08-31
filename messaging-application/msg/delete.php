<?php
require '../db.php';

$sql = "DELETE FROM empuk_email WHERE emailID = " . $_POST['msgid'];
$q = $conn->query($sql);
if ($q)
	echo "Message deleted.";
else
	echo $conn->error;

$conn->close();

?>