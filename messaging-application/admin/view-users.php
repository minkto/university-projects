<link type="text/css" rel="stylesheet" href="../style.css" />

<?php
require '../db.php';

session_start();

if($_SESSION['role'] == "Admin"){

	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	//selects all users from the database.
	$sql = "SELECT id, username, forename, surname, email, role, last_active, time_locked FROM empuk_users";
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		echo "<table id='user-table' align=center><tr id='usertable-toprow'><td>ID</td><td>Username</td><td>Full name</td><td>E-mail address</td><td>Role</td><td>Last active</td><td>Unlock time</td><td></td></tr>";
	
		//displays all users in a table.
		while($row = $result->fetch_assoc()) {
			echo "<tr id='usertable-datarow'>
			<td>" . $row['id'] . "</td>
			<td>" . $row['username'] . "</td>
			<td>" . $row['forename'] . " " . $row['surname'] .  "</td>
			<td>" . $row['email'] . "</td>
			<td>" . $row['role'] . "</td>
			<td>" . $row['last_active'] . "</td>
			<td>" . $row['time_locked'] . "</td>";
				
			if ($row['role'] == "Admin"){
				echo "<td></td></tr>";
			}
				
			else {
				echo "<td><a href='admin/user-actions.php?id=" . $row['id'] . "'>Actions</a></td></tr>";
			}
		}
		
		echo "</table>";
	}

	else
	{
		echo $conn->error;
	}
		
	$conn->close();
}

else {
	header("Location: ../index.php");
}
?>