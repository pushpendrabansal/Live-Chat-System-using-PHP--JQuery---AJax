<?php
include'class/user.php';
session_start();
if(!isset($_SESSION['user']))
{
 header('location:login.php');
}
$user_id=$_SESSION['user'];
$user = new USER();

//fetch user details
$result=$user->user_detail($user_id);

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>	
    <script type="text/javascript" src="function.js"></script>
    <script src="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

</head>
<body>
	
	<div class="header">
		<a href="Logout.php">Logout</a>
		<a href="">Hi <?php echo($result['username']); ?></a>
	</div>
	
	<div id="user_details"></div>
	<div id="user_model_details"></div>


</body>
</html>