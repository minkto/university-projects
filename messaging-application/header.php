<?php
session_start();
session_regenerate_id();

//if the user logs out (by pressing "Log out"),
//then the user is removed from the online user table and the session is destroyed.
if(isset($_GET['logout'])){	
	$sql = 'UPDATE empuk_users SET online = FALSE WHERE id = "' . $_SESSION["id"] . '"';
	if ($conn->query($sql) === TRUE)
	{
		session_destroy();
		header("Location: index.php");
	}
		
	else
	{
		echo "<p>An error occurred: " . $conn->error .  "</p>"; //throw an error if sql decides it doesn't want to work.
	}
}
?>

<div id = "header">	
		<img class="logo" src="mainLogo.png" alt="EmployablilityUK">
		
		<?php
		if (isset($_SESSION["name"])) //checks to see if the user is logged in and shows a message depending on the type of user that is logged in.
		{
			if($_SESSION["role"] == "Admin")
			{
				echo "<div class='welcome'>Welcome, <b>[Admin] " . $_SESSION['fname'] . " " . $_SESSION['sname'] . "</b> (" . $_SESSION['name'] . ") [<a id='show-settings' href='#'>Settings</a>] [<a id='exit' href='#'>Log out</a>]<br>
				[<a href='admin.php'>Admin panel</a>] ";
			}
			
			else if($_SESSION["role"] == "Mentor")
			{
				echo "<div class='welcome'>Welcome, <b>[Mentor] " . $_SESSION['fname'] . " " . $_SESSION['sname'] . "</b> (" . $_SESSION['name'] . ") [<a id='show-settings' href='#'>Settings</a>] [<a id='exit' href='#'>Log out</a>]<br>
				[<a href='mentor.php'>Mentor panel</a>] ";
			}
			
			else
			{
				echo "<div class='welcome'>Welcome, <b>" . $_SESSION['fname'] . " " . $_SESSION['sname'] . "</b> (" . $_SESSION['name'] . ") [<a id='show-settings' href='#'>Settings</a>] [<a id='exit' href='#'>Log out</a>]<br>";
			}
			
			echo "[<a class='compose' href='#'>Compose</a>] [<a href='index.php?box=1'>Inbox</a>] [<a href='index.php?box=2'>Sent items</a>] [<a href='index.php?box=3'>Drafts</a>] [<a href='#' class='show-contacts'>Contacts</a>]</div>";
		}
		else //if the user is not logged in
		{
			echo "<p class='welcome'>Welcome. Please [<a href='index.php'>sign in</a>] or [<a href='reg.php'>register</a>] to use the system.</p>";
		}
		?>
</div>

<div id="settings">
	<table align=center>
		<tr>
			<td align=right>Header colour:</td>
			<td><input type='color' class='hcolour' /></td>
		</tr>
		<tr>
			<td align=right>Header font colour:</td>
			<td><input type='color' class='hfcolour' /></td>
		</tr>
		<tr>
			<td align=right>Header link colour:</td>
			<td><input type='color' class='lcolour' /></td>
		</tr>
		<tr>
			<td align=right>Font colour:</td>
			<td><input type='color' class='fcolour' /></td>
		</tr>
		<tr>
			<td align=right>Font size</td>
			<td>
			<select class='fsize' value='100%'>
				<option value='80%'>Smaller</option>
				<option value='90%'>Small</option>
				<option value='100%' selected='selected'>Normal</option>
				<option value='110%'>Large</option>
				<option value='120%'>Larger</option>
			</select>
			</td>
		</tr>
	</table>
	<input type='submit' class='s-default' value='Defaults' />
	<input type='submit' class='s-close' value='Close' />
</div>

<div id="contacts"></div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<!-- including the js-cookie API. see https://github.com/js-cookie/js-cookie -->
<script src="js/js.cookie.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		
		// This first part deals with inactive activity.
		
		idleMax = 20;// This means to logout after 20 mins
		idleTime = 0;// Sets initial time.

		
		setInterval(timerIncrement, 60000); // Second parameter is in Milliseconds
		$(this).mousemove(function (e) {idleTime = 0;}); // If mouse is moved then reset idle time to 0
		$(this).keypress(function (e) {idleTime = 0;}); // If a key is pressed then reset idle time to 0
		
		function timerIncrement() {
			idleTime = idleTime + 1;
			if (idleTime > idleMax) { 
				if(<?php echo isset($_SESSION['name']); ?>){ // Finally If logged in and still inactive,
															 // Take the user back to the login screen.
					window.location="index.php?logout=true&type=auto";
				} 
			}
		} // end timerIncrement(..)
		
		//fetching user-defined style settings via cookies
		var hcolour = Cookies.get('hcolour');
		var hfcolour = Cookies.get('hfcolour');
		var fcolour = Cookies.get('fcolour');
		var lcolour = Cookies.get('lcolour');
		var fsize = Cookies.get('fsize');
		setSizes(fsize);
		
		//applying custom style settings if they have been set.
		$("#header, #footer").css({"background-color":hcolour, "color":hfcolour});
		$("body").css({"color":fcolour, "font-size":fsize});
		$("a").css("color", lcolour);
		
		//shows the settings panel when the user clicks "Settings"
		$("#show-settings").click(function() {
			$("#settings").toggle();
			$("#contacts").hide();
			
			//setting the values in the settings panel
			$(".hcolour").val(Cookies.get('hcolour'));
			$(".hfcolour").val(Cookies.get('hfcolour'));
			$(".fcolour").val(Cookies.get('fcolour'));
			$(".lcolour").val(Cookies.get('lcolour'));
			$(".fsize").val(Cookies.get('fsize'));
		});
		
		//updates header/footer colour when the user selects one
		$(".hcolour").change(function() {
			var colour = $(".hcolour").val();
			Cookies.set('hcolour', colour); //setting a cookie so the style is preserved across pages.
			$("#header, #footer").css("background-color", colour);
		});
		
		//updates header font colour
		$(".hfcolour").change(function() {
			var colour = $(".hfcolour").val();
			Cookies.set('hfcolour', colour); //setting a cookie so the style is preserved across pages.
			$("#header, #footer").css("color", colour);
		});
		
		//updates font colour
		$(".fcolour").change(function() {
			var colour = $(".fcolour").val();
			Cookies.set('fcolour', colour);
			$("body").css("color", colour);
		});
		
		//link colour
		$(".lcolour").change(function() {
			var colour = $(".lcolour").val();
			Cookies.set('lcolour', colour);
			$("a").css("color", colour);
		});
		
		//font size
		$(".fsize").change(function() {
			var size = $(".fsize").val();
			setSizes(size);
			Cookies.set('fsize', size);
			$("body").css("font-size", size);
		});
		
		//sets various css (e.g. header/footer height, inbox/message box height etc) based on the selected font size
		function setSizes(size){
			switch(size) {
				case "80%":
					$("#header, #footer, #loginform, #box-wrapper").css("min-width", "450px");
					$("#header").css("height", "50px");
					$("#footer").css("height", "28px");
					$(".logo").css("padding-top", "6px");
					$("#box-wrapper").css({"padding-top":"55px", "height":"calc(100% - 60px)"});
					break;
				case "90%":
					$("#header, #footer, #loginform, #box-wrapper").css("min-width", "500px");
					$("#header").css("height", "50px");
					$("#footer").css("height", "31px");
					$(".logo").css("padding-top", "6px");
					$("#box-wrapper").css({"padding-top":"55px", "height":"calc(100% - 60px)"});
					break;
				case "110%":
					$("#header, #footer, #loginform, #box-wrapper").css("min-width", "600px");
					$("#header").css("height", "55px");
					$("#footer").css("height", "37px");
					$(".logo").css("padding-top", "9px");
					$("#box-wrapper").css({"padding-top":"60px", "height":"calc(100% - 65px)"});
					break;
				case "120%":
					$("#header, #footer, #loginform, #box-wrapper").css("min-width", "650px");
					$("#header").css("height", "60px");
					$("#footer").css("height", "40px");
					$(".logo").css("padding-top", "11px");
					$("#box-wrapper").css({"padding-top":"65px", "height":"calc(100% - 70px)"});
					break;
				default:
					$("#header, #footer, #loginform, #box-wrapper").css("min-width", "550px");
					$("#header").css("height", "50px");
					$("#footer").css("height", "34px");
					$(".logo").css("padding-top", "6px");
					$("#box-wrapper").css({"padding-top":"55px", "height":"calc(100% - 60px)"});
			}
		}
		
		//adjusts inbox/message box height when the footer is hidden (lower than 750px in height).
		//also hides/shows the logo in the header if there isn't/is enough space to show it, based off the specified font sizes.
		setInterval(function(){
			switch(Cookies.get('fsize')) {
				case "80%":
					var height = "calc(100% - 44px)";
					var width = "750px";
					break;
				case "90%":
					var height = "calc(100% - 47px)";
					var width = "800px";
					break;
				case "110%":
					var height = "calc(100% - 53px)";
					var width = "900px";
					break;
				case "120%":
					var height = "calc(100% - 56px)";
					var width = "950px";
					break;
				default:
					var height = "calc(100% - 50px)";
					var width = "850px";
			}
			
			if(window.matchMedia("(max-height:750px)").matches)
				$("#inbox, #message").css("height", "100%");
			else
				$("#inbox, #message").css("height", height);
			
			if(window.matchMedia("(max-width:" + width + ")").matches)
				$(".logo").css("display", "none");
			else 
				$(".logo").css("display", "block");
		},250);
		
		//reverts all styles back to default.
		$(".s-default").click(function() {
			Cookies.remove('hcolour');
			Cookies.remove('hfcolour');
			Cookies.remove('fcolour');
			Cookies.remove('lcolour');
			Cookies.remove('fsize');
			setSizes("100%");
			$("#header, #footer").css("background-color", "#d8d8d8");
			$("body, #header, #footer").css("color", "#222");
			$("a").css("color", "#CC027B");
			$("body").css("font-size", "100%");
		});
		
		//closes the settings window.
		$(".s-close").click(function() {
			$("#settings").hide();
		});
		
		$(".show-contacts").click(function() {
			$("#contacts").toggle();
			$("#settings").hide();
			
			$.ajax({
				url: "contacts.php",
				cache: true,
				success: function(html){		
					$("#contacts").html(html); //inserts the messages in to the div.
				}
			});
		});
		
		//displays a logout prompt when the user clicks "Log out"
		$("#exit").click(function(){
			var exit = confirm("Are you sure you want to log out?");
			if(exit==true){window.location = 'index.php?logout=true';}		
		});
		
		$(".compose").click(function() {
			window.open('compose.php', 'newwindow', 'width=800, height=600'); //opens a message window.
			return false;
		});
	});
</script>

