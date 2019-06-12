<?php
	
session_start();

$id = $_SESSION['id'];

if(isset($_POST["submit"])) {

	move_uploaded_file($_FILES["image"]["tmp_name"], "img/$id.png");
	unset($_POST["submit"]);

	header('Location: account.php');
}
 
?>