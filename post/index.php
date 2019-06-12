<?php
	
	error_reporting(0);
	
	session_start();
	$id = $_GET['id'];

	require_once "../connect.php";

	$mysqli = @new mysqli($host, $db_user, $db_password, $db_name);


?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<title>FORUM</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="../files/main-style.css">
	<link rel="stylesheet" type="text/css" href="../files/post-style.css">
</head>
<body>


	<div class="row navbar">
		<div class="col-md-2" id="logo"><a href="../index.php">FORUM.PL</a></div>
		<div class="col-md-1"></div>
		<input type="text" class="col-md-5 form-control" name="search" placeholder="Search"/>
		
		<?php
			if (isset($_SESSION['zalogowany']))
			{
				echo "<div class='col-md-2 nav-login'><a href='../account.php'>";
					echo $_SESSION['login']." ";
					echo "<img class='profile-nav' src='../img/".$_SESSION['id'].".png' onerror='defaultImg()'/>";
				echo "</a></div>";

				echo "<div class='col-md-1 log'><a href='../logout.php'>Logout</a></div>";
			}
			else
			{
				echo "<div class='col-md-2'></div>";
				echo "<div class='col-md-1 log'><a href='../login.php'>Login</a></div>";
			}
		?>
		<div class="col-md-1 log"><a href="register">Register</a></div>
	</div>

	<div class="container">

	<?php  

		$topic = $mysqli->query("SELECT pytanie, opis, kategoria, id_user FROM topics WHERE id=$id");

		$answer = $mysqli->query("SELECT id_user, content FROM answer WHERE id_topic=$id");

		$r_t = $topic->fetch_assoc();

		$temp = $r_t['id_user'];

		$users = $mysqli->query("SELECT login FROM users WHERE id = $temp");
		$r_u = $users->fetch_assoc();


		echo "<div class='list'>";
			echo "<div class='row'>";
				echo "<span class='list-question-post col-lg-8'>".$r_t["pytanie"]."</span>";

				echo "<span class='list-info col-lg-4'>Dodane przez: <span class='list-moreinfo'>".$r_u["login"].' '."</span>";

				echo "Kategoria: <span class='list-moreinfo'>".$r_t["kategoria"]."</span></span><br/>";

			echo "</div>";

			echo "<div class='row'>";
			    echo "<span class='question-desc'>".$r_t["opis"]."</span>";
			echo "</div>";
		echo "</div>";



		if (isset($_SESSION['zalogowany'])) {
			
			$_SESSION['id_user_topic'] = $temp;
			$_SESSION['topic_id'] = $id;

			echo "<div class='row'>";
				echo "<button class='btn btn-outline-primary' id='make_que'>Dodaj odpowiedź</button>";
			echo "</div>";

			echo "<form class='row question-form' method='POST' action='../add-answer.php'>";
				echo "<textarea class='in-form' id='desc' name='content' placeholder='Treść odpowiedzi' maxlength='1000'></textarea></br>";
				echo "<input class='in-form' type='submit' value='Wyślij'>";
			echo "</form>";
		}



		if (isset($_SESSION['add_answer_info'])) {

			echo $_SESSION['add_answer_info'];

			unset($_SESSION['add_answer_info']);
		}

		echo "<h2>Odpowiedzi:<h2>";

		$empty = true;

		while ($r_a = $answer->fetch_assoc()) {

			$empty=false;

			$temp2 = $r_a["id_user"];
			$users_odp = $mysqli->query("SELECT login FROM users WHERE id = $temp2");

			$id_user_odp = $users_odp->fetch_assoc();

		    echo "<div class='answer'>";
				echo "<div class='row'>";
				    echo "<span class='list-info col-lg-12'>Dodane przez: <span class='user_odp'>".$id_user_odp["login"].' '."</span>";
			    echo "</div>";

			    echo "<div class='row'>";
			    	echo "<span class='answer-desc col-lg-12'>".$r_a["content"]."</span>";
			    echo "</div>";
		    echo "</div>";
		}

		if ($empty == true && !isset($_SESSION['zalogowany'])) {
			echo "Zaloguj się aby udzielić odpowiedzi";
		}

		$mysqli->close();
	?>

	</div>


<script>
	
	function defaultImg() {
        $(".profile").src = "../img/default.png";
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