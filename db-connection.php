<?php

// Local DB

const hostname = 'localhost';
const username = 'root';
const password = '';
const dbname = 'm151_chatattack_db';

// Global DB

// const hostname = 'xucatoni.mysql.db.internal';
// const username = 'xucatoni_admin';
// const password = 'egsvHMdr';
// const dbname = 'xucatoni_chatattack';

try{

    $pdo = new PDO("mysql:host=" . hostname . ";dbname=" . dbname, username, password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e){

    die("ERROR: Could not connect. " . $e->getMessage());

}

function fetch_users($pdo) {

	$query = "SELECT * FROM user WHERE id != '".$_SESSION['id']."' AND id != 0";

	$statement = $pdo->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	$output = '
	<ul>
		<li id="globalchatlink">
			<a href="?uid=0">Enter Global Chat</a>
		</li>
	';

	foreach($result as $row)
	{
		$output .= '
		<li>
			<a href="?uid=' . $row['id'] . '">' . $row['username'] . '</a>
		</li>
		';
	}

	$output .= '</ul>';

	return $output;

}

function fetch_chat($pdo, $from_user_id, $to_user_id)
{
	$query = "
	SELECT * FROM chat
	WHERE (from_user = '".$from_user_id."' 
	AND to_user = '".$to_user_id."') 
	OR (from_user = '".$to_user_id."' 
	AND to_user = '".$from_user_id."') 
	ORDER BY time DESC
	";
	$statement = $pdo->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '<ul class="chathistory">';
	foreach($result as $row)
	{
		$user_name = '';
		$dynamic_background = '';
		$chat_message = '';
		if($row["from_user"] == $from_user_id)
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b>You</b>';
			}
			else
			{
				$chat_message = $row['msg'];
				$user_name = '<b>You</b>';
			}
			$output .= '
			<li class="chatmessageown">
				<p>' . $chat_message . '
					<div>
						- <small><em>'.$row['time'].'</em></small>
					</div>
				</p>
			</li>
			';
			
		}
		else
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
			}
			else
			{
				$chat_message = $row["msg"];
			}
			$user_name = '<b>'.get_user_name($row['from_user'], $pdo).'</b>';
			$output .= '
			<li class="chatmessage">
				<p>' .$chat_message. '
					<div>
						- <small><em>'.$row['time'].'</em></small>
					</div>
				</p>
			</li>
			';
		}
	}
	$output .= '</ul>';
	$query = "
	UPDATE chat
	SET status = '0' 
	WHERE from_user = '".$to_user_id."' 
	AND to_user = '".$from_user_id."' 
	AND status = '1'
	";
	$statement = $pdo->prepare($query);
	$statement->execute();
	return $output;
}

function get_user_name($user_id, $pdo)
{
	$query = "SELECT username FROM user WHERE id = $user_id";
	$statement = $pdo->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['username'];
	}
}

function fetch_group_chat_history($pdo)
{
	$query = "
	SELECT * FROM chat
	WHERE to_user = '0'  
	ORDER BY time DESC
	";

	$statement = $pdo->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	$output = '<ul class="list-unstyled">';


	foreach($result as $row)
	{
		$user_name = '';
		$dynamic_background = '';
		$chat_message = '';
		if($row["from_user"] == $_SESSION["id"])
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b class="text-success">You</b>';
			}
			else
			{
				$chat_message = $row["msg"];
				$user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['id'].'">x</button>&nbsp;<b class="text-success">You</b>';
			}
			
			$dynamic_background = 'background-color:#ffe6e6;';
		}
		else
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
			}
			else
			{
				$chat_message = $row["msg"];
			}
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user'], $pdo).'</b>';
			$dynamic_background = 'background-color:#ffffe6;';
		}

		$output .= '

		<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
			<p>'.$user_name.' - '.$chat_message.' 
				<div align="right">
					- <small><em>'.$row['time'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	return $output;
}

?>