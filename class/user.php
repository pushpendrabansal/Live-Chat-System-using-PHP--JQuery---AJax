<?php
include('config.php');

date_default_timezone_set('Asia/Kolkata');
class user{

	private $pdo;

	function __construct(){
		$conf = new config();
		$db=$conf->db();	
		$this->pdo=$db;
	}

	function runQuery($sql){
		return $this->pdo->prepare($sql);
	}

	function user_name($user_id){
		$sql = "SELECT * FROM login where user_id=:user_id";
		$query= $this->pdo->prepare($sql);
		$query->bindParam(':user_id',$user_id,PDO::PARAM_STR);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_ASSOC);
		return $result['username'];
	}	

	function user_detail($user_id){
		$sql = "SELECT * FROM login where user_id=:user_id";
		$query= $this->pdo->prepare($sql);
		$query->bindParam(':user_id',$user_id,PDO::PARAM_STR);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	function login_check($username,$password){
		$sql = "SELECT * FROM login WHERE username=:username and password=:password";
		$query= $this->pdo->prepare($sql);
		$query->bindParam(':username',$username,PDO::PARAM_STR);
		$query->bindParam(':password',$password,PDO::PARAM_STR);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['user']=$result['user_id'];
		return $result['user_id'];
	}

	function insert_login_details($user_id){
		$sql = "INSERT INTO login_details(user_id)VALUES (:id)";
		$query= $this->pdo->prepare($sql);
		$query->bindParam(':id',$user_id,PDO::PARAM_STR);
		$query->execute();
		$_SESSION['login_details_id'] = $this->pdo->lastInsertId();
	}

	function fetch_last_activity($user_id){
		$sql = "SELECT * FROM login_details where user_id=:user_id order by last_activity DESC LIMIT 1";
		$query =$this->pdo->prepare($sql);
		$query->bindParam(':user_id',$user_id,PDO::PARAM_STR);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_ASSOC);	
		return $result['last_activity'];
	}

	function chat_history($user,$to_user){
		$sql = "SELECT * FROM chat_message WHERE (to_user_id=:to_user_id and from_user_id=:from_user_id) or (to_user_id=:from_user_id and from_user_id=:to_user_id) order by timestamp desc";
		$query=$this->pdo->prepare($sql);
		$query->bindParam(':to_user_id',$to_user,PDO::PARAM_STR);
		$query->bindParam(':from_user_id',$user,PDO::PARAM_STR);
		$query->execute();
		

		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		$output = '<ul class="list-unstyled">';
		 foreach($results as $row)
		 {
		  $user_name = '';
		  if($row["from_user_id"] == $user)
		  {
		   $user_name = '<b class="text-success">You</b>';
		  }
		  else
		  {
		   $user_name = '<b class="text-danger">'.$this->user_name($row['from_user_id']).'</b>';
		  }
		  $output .= '
		  <li style="border-bottom:1px dotted #ccc">
		   <p>'.$user_name.' - '.$row["chat_message"].'
		    <div align="right">
		     - <small><em>'.$row['timestamp'].'</em></small>
		    </div>
		   </p>
		  </li>
		  ';
		 }
		 $output .= '</ul>';
		 $query = "UPDATE chat_message SET status = '0' WHERE from_user_id = '".$to_user."' AND to_user_id = '".$user."' AND status = '1' ";
		 $statement = $this->pdo->prepare($query);
		 $statement->execute();
		 return $output;
	}

	function count_unseen_message($user,$to_user){
		$sql = "SELECT * FROM chat_message WHERE from_user_id=:from_user_id and to_user_id=:to_user_id and status='1'";
		$query=$this->pdo->prepare($sql);
		$query->bindParam(':from_user_id',$user,PDO::PARAM_STR);
		$query->bindParam(':to_user_id',$to_user,PDO::PARAM_STR);
		$query->execute();
		$count = $query->rowCount();
		$output = '';
		if($count>0){
			$output = '<span class="label label-success">'.$count.'</span>';
		}
		return $output;
	}

	function fetch_is_type_status($user_id)
	{
	 $sql = "SELECT is_type FROM login_details WHERE user_id=:user_id ORDER BY last_activity DESC LIMIT 1 "; 
	 $query = $this->pdo->prepare($sql);
	 $query->bindParam(':user_id',$user_id,PDO::PARAM_STR);
	 $query->execute();
	 $result = $query->fetchAll();
	 $output = '';
	 foreach($result as $row)
	 {
	  if($row["is_type"] == 'yes')
	  {
	   $output = ' - <small><em><span class="text-muted">Typing...</span></em></small>';
	  }
	 }
	 return $output;
	}

}
?>