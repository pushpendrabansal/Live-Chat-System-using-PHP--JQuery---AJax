<?php
date_default_timezone_set('Asia/Kolkata');
include 'class/user.php';
session_start();
if(!isset($_SESSION['user']))
{
 header('location:login.php');
}
$user_id=$_SESSION['user'];

$db = new config();
$pdo = $db->db();

$user = new user();

$sql = "SELECT * FROM login WHERE user_id!=:user_id";
$query= $user->runQuery($sql);
$query->bindParam(':user_id',$user_id,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

?>

	<div class="details">
		<table class="table table-bordered table-striped">
			

  			<tr>
  				<td>Users</td>
  				<td>Status</td>
  				<td>Active</td>
  			</tr>
<?php	foreach ($results as $row) {  
				$status = '';
				$current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
				$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
				$user_last_activity = $user->fetch_last_activity($row->user_id);
				if($user_last_activity > $current_timestamp)
				{
				  $status = '<span class="label label-success">Online</span>';
				}
				else
				{
				  $status = '<span class="label label-danger">Offline</span>';
				} 
?>
			<tr>
				<td>
					<?php echo $row->username ; ?>
					<?php echo $user->count_unseen_message($row->user_id,$user_id) ; ?>
					<?php echo $user->fetch_is_type_status($row->user_id) ; ?>
				</td>
				<td>
					<?php echo $status ; ?>
				</td>
				<td>
					<button type="button" class="btn btn-info btn-xs start_chat" data-touserid="<?php echo $row->user_id ;?>" data-tousername="<?php echo $row->username ;?>">Start Chat</button>
				</td>
			</tr>
<?php 	} 	?>			
		</table>
	</div>
