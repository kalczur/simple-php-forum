<?php

	session_start();

	if (isset($_GET['page'])) {
		$page = $_GET['page'] * 10;
	}
	else $page = 10;

	require_once "connect.php";

	$mysqli = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if(isset($_POST['topic'])){

		$topic = $_POST['topic'];
		$desc = $_POST['desc'];
		$desc = htmlspecialchars($desc);

		$choice = $_POST['choice-cat'];
		$id_user = $_SESSION['id'];

		if($mysqli->query("INSERT INTO topics VALUES (NULL, '$id_user', '$topic', '$desc', '$choice', 0)"))
		{
			unset($_GET['topic']);
			header('Location: index.php');
		}
	}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<title>FORUM</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="files/main-style.css">
</head>
<body>


	<div class="row navbar">
		<div class="col-md-2" id="logo"><a href="index.php">FORUM.PL</a></div>
		<div class="col-md-1"></div>
		<input type="text" class="col-md-5 form-control" name="search" placeholder="Search"/>
		
		<?php
			if (isset($_SESSION['zalogowany']))
			{
				echo "<div class='col-md-2 nav-login'><a href='account.php'>";
					echo $_SESSION['login']." ";
					echo "<img class='profile-nav' src='img/".$_SESSION['id'].".png' onerror='defaultImg()'/>";
				echo "</a></div>";

				echo "<div class='col-md-1 log'><a href='logout.php'>Logout</a></div>";
			}
			else
			{
				echo "<div class='col-md-2'></div>";
				echo "<div class='col-md-1 log'><a href='login.php'>Login</a></div>";
			}
		?>
		<div class="col-md-1 log"><a href="reg.php">Register</a></div>
	</div>

	<div class="container">
		<form method="POST">
		<div class="row category">
			
				<input type="submit" name="select-cat" class="col-xs-12 col-lg-2 cat-info" value="Wszystkie">
				<input type="submit" name="select-cat" class="col-xs-12 col-lg-2 cat-info" value="Systemy">
				<input type="submit" name="select-cat" class="col-xs-12 col-lg-2 cat-info" value="Smartfony">
				<input type="submit" name="select-cat" class="col-xs-12 col-lg-2 cat-info" value="Gry">
				<input type="submit" name="select-cat" class="col-xs-12 col-lg-2 cat-info" value="Zdjęcia/Wideo">
				<input type="submit" name="select-cat" class="col-xs-12 col-lg-2 cat-info" value="Inne">
			
		</div>
		</form>
			
		<?php

		if (isset($_SESSION['zalogowany'])){
			echo "<div class='row'>";
				echo "<button class='btn btn-outline-primary' id='make_que'>Zadaj pytanie</button>";
			echo "</div>";

			echo "<form class='row question-form' method='POST'>";
				echo "<input class='in-form' type='text' name='topic' placeholder='Tytuł pytania' maxlength='150'></br>";
				echo "<textarea class='in-form' id='desc' name='desc' placeholder='Opis pytania' maxlength='620'></textarea></br>";
				echo "Kategoria:";
				echo "<select class='in-form' name='choice-cat'>";
					echo "<option value='Systemy'>Systemy</option>";
					echo "<option value='Smartfony'>Smartfony</option>";
					echo "<option value='Gry'>Gry</option>";
					echo "<option value='Zdjęcia/Wideo'>Zdjęcia/Wideo</option>";
					echo "<option value='Inne'>Inne</option>";
				echo "</select>";
				echo "<input class='in-form' type='submit' value='Wyślij'>";
			echo "</form>";
		}

		?>
		

		<?php  
			
			if(isset($_POST['select-cat']) && $_POST['select-cat']!='Wszystkie'){
				$cat = $_POST['select-cat'];
			}
			else{

				$cat = "%";
			}
			

			/*$count = $mysqli->query("SELECT COUNT(*) FROM topics");
			$c_topics = $count->fetch_assoc();
			$c_t = $c_topics['COUNT(*)'] / 10;*/

			$page_temp = $page - 10;

			$z = $mysqli->query("SELECT pytanie, opis, kategoria, id_user, id, ile_odp FROM topics WHERE kategoria LIKE '$cat' ORDER BY id DESC LIMIT $page_temp,$page");

			$c = $mysqli->query("SELECT COUNT(*) FROM topics WHERE kategoria LIKE '$cat'");
			$c_tab = $c->fetch_assoc();
			$count = ($c_tab['COUNT(*)'] / 10)+1;

			while (($r = $z->fetch_assoc())) {

				$temp = $r['id_user'];
				$question_id = $r['id'];
				$x = $mysqli->query("SELECT login FROM users WHERE id = $temp");
				$y = $x->fetch_assoc();

				echo "<div class='list'>";
					echo "<div class='row'>";
					    echo "<a href='post/index.php?id=$question_id' class='list-question col-lg-8'>".$r["pytanie"]."</a>";

					    echo "<span class='list-info col-lg-4'>Dodane przez: <span class='list-moreinfo'>".$y["login"].' '."</span>";

					    echo "Kategoria: <span class='list-moreinfo'>".$r["kategoria"]."</span></span><br/>";

				    echo "</div>";

				    echo "<div class='row'>";
				    	echo "<span class='list-desc d-lg-inline d-sm-none col-lg-10'>".$r["opis"]."</span>";
				    	echo "<span class='list-points col-lg-2'>Odpowiedzi: ".$r["ile_odp"]."</span>";
				    echo "</div>";
			    echo "</div>";
			}
			


			$i = 1;

			echo "<div class='page-nav'>";
				while ($i < $count) {
					echo "<a href='index.php?page=".$i."' class='page-nav-item'>".$i."</a>";
					$i++;
				}
			echo "</div>";



			$z->free();
			$mysqli->close();
		?>

	</div>

<script>
	
	function defaultImg() {
        $(".profile-nav").attr("src", "img/default.png");
    }

	$("#make_que").click(function() {

		var $this = $(".question-form");

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