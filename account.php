<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Profil</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="files/main-style.css">
	<link rel="stylesheet" type="text/css" href="files/acc-style.css">
</head>
<body>

	<div class="row navbar">
		<div class="col-md-2" id="logo"><a href="index.php">FORUM.PL</a></div>
		<div class="col-md-1"></div>
		<input type="text" class="col-md-5 form-control" name="search" placeholder="Search"/>
		

		<div class='col-md-2 nav-login'>
			<a href='account.php'/>
				<?php echo $_SESSION['login']." ";?>
				<img class='profile-nav' src="img/<?php echo $_SESSION['id']?>.png" onerror="defaultImg()"/>
			</a>
		</div>


		<div class='col-lg-1 col-sm-12 log'><a href='logout.php'>Logout</a></div>
		<div class="col-lg-1 col-sm-12 log"><a href="reg.php">Register</a></div>
	</div>

	<div class="container">

		<div class="row acc-general">
			
			<img id="profile" class="d-lg-2" src="img/<?php echo $_SESSION['id'] ?>.png" onerror="defaultImg();"/>
			
			<div class="col-lg-4 col-sm-3">
				Witaj <?php echo $_SESSION['login'] ?>
			</div>

			<div class="col-md-6">
				Imię: <?php echo $_SESSION['imie'] ?> <br/>
				Nazwisko: <?php echo $_SESSION['nazwisko'] ?> <br/>
				E-mail: <?php echo $_SESSION['mail'] ?> <br/>
				Punkty: <?php echo $_SESSION['punkty'] ?>
			</div>
		</div>

		<div class="row">
			<button class="btn change-img btn-outline-primary col-md-2" id="make_que">Zmień zdjęcie</button>
		</div>


		<form action="upload.php" class="upload-form col-md-10" method="post" enctype="multipart/form-data">
		    <input type="file" name="image" id="fileToUpload"><br/><br/>
		    <input type="submit" value="Wyślij" name="submit">
		</form>


		

	</div>

<script>
    
    function defaultImg() {
    	$('.profile-nav').attr("src", "img/default.png");
        $('#profile').attr("src", "img/default.png");
    }

	$("#make_que").click(function() {

		var $this = $(".upload-form");

	    if ($this.hasClass("clicked-once")) {
	        $this.removeClass("clicked-once");
	    }
	    else {
	        $this.addClass("clicked-once");
	    }
	});
</script>
</body>
</html>