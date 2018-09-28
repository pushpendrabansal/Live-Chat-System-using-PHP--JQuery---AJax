<?php

session_start();

unset($_SESSION['user']);
unset($_SESSION['login_details_id']);
header('location:index.php');

?>
