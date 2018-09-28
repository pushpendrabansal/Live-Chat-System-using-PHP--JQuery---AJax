<?php
require_once 'class/user.php';
session_start();
$msg='';
if(isset($_SESSION['user']))
{
 header('location:index.php');
}
$user = new user();

if(isset($_POST['submit'])){

	$username=$_POST['username'];
	$password=$_POST['password'];

	$logincheck = $user->login_check($username,$password);

	if($logincheck){
		$user->insert_login_details($logincheck);
		header('location:index.php');
	}
	else{
		$msg="Wrong Credinatials";
	}

}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat Message</title>
	<style type="text/css">
		input[type="text"],input[type="password"]{
			padding: 6px 14px 6px 14px;
			width: 51%;
			margin: 15px;
		}
		input[type="submit"]{
			background: #eff3ff	;
			padding: 2px 6px 2px 6px;	}	
	</style>
</head>
<body>

	<div style="position: absolute;top: 30%;left: 30%;border:2px solid #f2f2f2;padding: 40px 10px;background: #EA907C;text-align: center">
		
		<form method="post" action="">
			<input type="text" name="username" placeholder="Enter username">
			<input type="password" name="password" placeholder="Enter Your password"> <br>

			<input type="submit" name="submit" value="submit"><br><br>
			<?php echo  $msg ? $msg : '' ;?>
		</form>
	</div>
		
</body>
</html>

