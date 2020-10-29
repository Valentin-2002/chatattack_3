<?php

//fetch_user.php

include('db-connection.php');

session_start();

$query = "SELECT * FROM user WHERE id != '".$_SESSION['id']."' AND id != 0";

$statement = $pdo->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '
<table class="table table-bordered table-striped">
	<tr>
		<th width="70%">Username</td>
		<th width="70%">Last Activity</td>
		<th width="10%">Action</td>
	</tr>
';

foreach($result as $row)
{
	$output .= '
	<tr>
		<td>'.$row['username']. '</td>
		<td>'.$row['last_activity']. '</td>
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['id'].'" data-tousername="'.$row['username'].'">Start Chat</button></td>
	</tr>
	';
}

$output .= '</table>';

echo $output;

?>