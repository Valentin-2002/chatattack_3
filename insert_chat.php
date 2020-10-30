<link rel="stylesheet" href="style.css">

<?php
require_once "db-connection.php";

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

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $from_user_id = $_SESSION['id'];
    $to_user_id = $_POST['uid'];
    $message = $_POST['message'];

    $sql = "INSERT INTO chat (from_user, to_user, msg) VALUES (:from_user_id, :to_user_id, :message)";

    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":from_user_id", $from_user_id);
        $stmt->bindParam(":to_user_id", $to_user_id);
        $stmt->bindParam(":message", $message);
        
        if($stmt->execute()){
            if($_POST['uid'] !== 0) {
                header("location: index.php?uid=" . $to_user_id);

            } else {
                header("location: index.php");
            }
        } else{
            echo "Something went wrong. Please try again later.";
        }
    } 
    
    unset($stmt);
    
    unset($pdo);  

}

