
<?php

include('db-connection.php');

session_start();

if(!isset($_SESSION['id']))
{
	header("location:login.php");
}

if(isset($_SESSION['role'])) {
	if($_SESSION['role'] == 1) {
		readfile("adminnavigation.html");
	} else {
		readfile("defaultnavigation.html");
	}
}

if(isset($_GET['uid'])) {
	$otherUserId = $_GET['uid'];
} else {
	$otherUserId = 0;
}

?>

<html>  
    <head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="style.css">
        <title>ChatAttack</title>  
    </head>  
    <body>  
		<div id="home">
			<div id="userlist">
				<?= fetch_users($pdo) ?>
			</div>
			<div id="chatbox">
				<?= fetch_chat($pdo, $_SESSION['id'], $otherUserId) ?>
				<form id="chatform" action="insert_chat.php" method="POST">
					<input id="messageinput" type="text" name="message" placeholder="Enter Message"></input>
					<input type="hidden" name="uid" value="<?= $otherUserId ?>"></input>
					<button id="sendbutton" type="submit" name="submit">
						<svg id="sendicon" enable-background="" height="24" viewBox="48 48" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m8.75 17.612v4.638c0 .324.208.611.516.713.077.025.156.037.234.037.234 0 .46-.11.604-.306l2.713-3.692z"/><path d="m23.685.139c-.23-.163-.532-.185-.782-.054l-22.5 11.75c-.266.139-.423.423-.401.722.023.3.222.556.505.653l6.255 2.138 13.321-11.39-10.308 12.419 10.483 3.583c.078.026.16.04.242.04.136 0 .271-.037.39-.109.19-.116.319-.311.352-.53l2.75-18.5c.041-.28-.077-.558-.307-.722z"/></svg>
					</button>
				</form>
			</div>
		</div>
    </body>  
</html>