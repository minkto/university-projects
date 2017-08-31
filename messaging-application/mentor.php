<?php
require 'db.php';
require 'header.php';
require 'footer.html';
require 'noscript.html';

//sends the user back to index.php if they are not a mentor (or not logged in).
function returnToIndex(){
	header("Location: index.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>EmployabilityUK [Mentor Panel]</title>
		<link type="text/css" rel="stylesheet" href="style.css" />
	</head>

	<body>
		<?php
			//returns the user to the index if they are not a mentor/logged in.
			if($_SESSION["role"] != "Mentor") {returnToIndex();}

			//displays the mentor panel.
			else {
		?>
		
		<div id="box-wrapper">
			<div id="loginform">
				<h1>Mentor panel</h1>
				<div id="users"></div>
			</div>
		</div>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){				
				$('#loginform').click(function(e){
					e.stopPropagation();
				})
				
				$.ajax({
					url: "mentor/view-student.php",
					cache: false,
					success: function(html){		
						$("#users").html(html); //inserts the list of users in to the div.
					}
				});				
			});
		</script>
	
		<?php
			}
		?>
	</body>
</html>