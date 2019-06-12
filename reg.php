<?php

	session_start();

	if(isset($_POST['name'])){

		$status = true;

		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$login = $_POST['login'];
		$mail = $_POST['mail'];
		$date = $_POST['date'];
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];

		//srp name
		if ( (strlen($name) < 3) || (strlen($name) > 20) ) {
			$status = false;
			$_SESSION['E_name'] = "Niedozwolona długość imienia";
		}

		//srp surname
		if ( (strlen($surname) < 3) || (strlen($surname) > 30) ) {
			$status = false;
			$_SESSION['E_surname'] = "Niedozwolona długość nazwiska";
		}

		//srp surname
		if ( (strlen($surname) < 3) || (strlen($surname) > 20) ) {
			$status = false;
			$_SESSION['E_surname'] = "Niedozwolona długość nazwiska";
		}

		//spr login
		if ( (strlen($login) < 3) || (strlen($login) > 20) ) {
			$status = false;
			$_SESSION['E_login'] = "Niedozwolona długość loginu";
		}

		if ( !ctype_alnum($login)) {
			$status = false;
			$_SESSION['E_login'] = "Login może zawierać tylko litery i cyfry";
		}

		//spr haslo
		if ((strlen($pass1)<8) || (strlen($pass1)>20))
		{
			$status=false;
			$_SESSION['E_pass']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($pass1!=$pass2)
		{
			$status=false;
			$_SESSION['E_pass']="Podane hasła nie są identyczne!";
		}	

		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//czy mail istnieje
				$rezultat = $polaczenie->query("SELECT id FROM users WHERE mail='$mail'");

				if( !$rezultat ) throw new Exception($polaczenie->error);

				$ilosc_maili = $rezultat->num_rows;
				if ( $ilosc_maili>0 ) {
					$status = false;
					$_SESSION['E_mail'] = "Konto z takim adresem e-mail już istnieje";
				}
				
				//czy login istnieje
				$rezultat = $polaczenie->query("SELECT id FROM users WHERE login='$login'");

				if( !$rezultat ) throw new Exception($polaczenie->error);

				$ilosc_loginow = $rezultat->num_rows;
				if ( $ilosc_loginow>0 ) {
					$status = false;
					$_SESSION['E_login'] = "Konto z takim loginem już istnieje";
				}

				//jesli sie udalo
				if ( $status == true) {
					

					if ( $polaczenie->query("INSERT INTO users VALUES (NULL, '$login', '$pass_hash', '$name', '$surname', '$mail', '$date', 300)")) 
					{
						$_SESSION['RegPass'] = true;
						header('Location: login.php');
					}

					else
					{
						throw new Exception($polaczenie->error);
					}

				}

				$polaczenie->close();
			}

		}

		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! 
			Spróbuj ponownie później!</span>';
			echo '<br />Informacja developerska: '.$e;
		}

	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Forum - Register</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="files/reg-style.css">
</head>
<body>

	<div class="logo"><a href="index.php">FORUM.PL</a></div>

	<form method="POST">
		<h2>Rejestracja</h2> <br/>
		<input class="half" type="text" name="name" placeholder="Imię"/>
		<?php
			if (isset($_SESSION['E_name']))
			{
				echo '<div class="error">'.$_SESSION['E_name'].'</div>';
				unset($_SESSION['E_name']);
			}
		?>

		<input class="half" type="text" name="surname" placeholder="Nazwisko"/>
		<?php
			if (isset($_SESSION['E_surname']))
			{
				echo '<div class="error">'.$_SESSION['E_surname'].'</div>';
				unset($_SESSION['E_surname']);
			}
		?>

		<input class="full" type="text" name="login" placeholder="login" />
		<?php
			if (isset($_SESSION['E_login']))
			{
				echo '<div class="error">'.$_SESSION['E_login'].'</div>';
				unset($_SESSION['E_login']);
			}
		?>

		<input class="full" type="email" name="mail" placeholder="e-mail" />
		<?php
			if (isset($_SESSION['E_mail']))
			{
				echo '<div class="error">'.$_SESSION['E_mail'].'</div>';
				unset($_SESSION['E_mail']);
			}
		?>

		<input class="half" type="password" name="pass1" placeholder="Hasło"/>
		<?php
			if (isset($_SESSION['E_pass']))
			{
				echo '<div class="error">'.$_SESSION['E_pass'].'</div>';
				unset($_SESSION['E_pass']);
			}
		?>
		<input class="half" type="password" name="pass2" placeholder="Powtórz hasło"/>

		<input class="full" type="text" name="date" placeholder="Data urodzenia ( rrrr-mm-dd )"/>
		<?php
			if (isset($_SESSION['E_date']))
			{
				echo '<div class="error">'.$_SESSION['E_date'].'</div>';
				unset($_SESSION['E_date']);
			}
		?>

		<input class="btn btn-outline-primary full" type="submit" value=" Zarejestruj ">
	</form>	

	<div id="link">
		Masz już konto? <a href="login.php">Zaloguj</a>
	</div>

</body>
</html>