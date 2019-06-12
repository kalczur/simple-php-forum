<?php

	session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: index-log.php');
		exit();
	}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Forum - Login</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="files/reg-style.css">
</head>
<body>

	<div class="col-md-12 logo"><a href="index.php">FORUM.PL</a></div>

	<form action="zaloguj.php" method="post">
		Login lub email<br/>
		<input class="form-control" type="text" name="login"/><br/>
		Hasło<br/>
		<input class="form-control" type="password" name="haslo"/><br/>

		<input class="btn btn-outline-primary full" type="submit" value=" Zaloguj ">

		<?php 
			if(isset($_SESSION['blad'])){
			echo $_SESSION['blad'];}
		?>

	</form>	

	<div id="link">
		Nowy na forum? <a href="reg.php">Stwórz konto</a>
	</div>

</body>
</html>