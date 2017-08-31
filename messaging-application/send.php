<?php
require 'db.php';
require 'word-filter.php';

session_start();

if(isset($_SESSION['name'])){
	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
		
	$sid = $_SESSION["id"]; //sender's user ID
	$recipient = stripslashes(htmlspecialchars(($_POST["recipient"]), ENT_QUOTES)); //recipient's username
	$subject = stripslashes(htmlspecialchars(($_POST["subject"]), ENT_QUOTES)); //the subject
	$msg = stripslashes(htmlspecialchars(($_POST["msg"]), ENT_QUOTES)); //the message itself. backslashes are removed and quotes (' ") are defined to prevent code injection
	$type = $_POST["type"]; //determining whether the user entered a username or an email address.

	//getting the recipient's user ID
	if ($type == "user")
		$sql = "SELECT id FROM empuk_users WHERE username = '" . $recipient . "'";
	else if ($type == "email")
		$sql = "SELECT id FROM empuk_users WHERE email = '" . $recipient . "'";
	else{
		echo "No send type was selected.";
		exit;
	}
	
	$result = $conn->query($sql);
	
	if($result->num_rows == 0){ //if the recipient user doesn't exist
		echo "That user does not exist. Make sure you typed their username/e-mail address correctly.";
		exit;
	}
	else {
		$row = $result->fetch_assoc();	
		$rid = $row['id']; //recipient's user ID
			
		//doesn't send anything if the message is empty.
		if (strlen($msg) == 0)
		{
			echo "Please enter a message.";
			exit;
		}
		
		else
		{
			
			$msgFiltered = filterWords($msg);
			$subjectFiltered = filterWords($subject);
			
			$date = date('Y-m-d H:i:s');
		
			if (isset($_POST['msgid'])) //updates the already-existing draft.
				$sql = "UPDATE empuk_email SET receiver = '" . $rid . "', subject = '" . $subjectFiltered . "', content = '" . $msgFiltered . "', time = '" . $date . "', draft = FALSE WHERE emailID = " . $_POST['msgid'];
			else //inserts the message in to the database.
				$sql = "INSERT INTO empuk_email (sender, receiver, subject, content, draft) VALUES ('" . $sid . "','" . $rid . "','" . $subjectFiltered . "','" . $msgFiltered . "', FALSE)";
			
			echo "success";
				
			$conn->query($sql);
		
		}
	}
	
	$conn->close();
}
?>