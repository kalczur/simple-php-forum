<?php

	session_start();
	require_once "connect.php";


	try
	{
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

		if ($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$content = $_POST['content'];
			$id_user = $_SESSION['id'];
			$topic_id = $_SESSION['topic_id'];

			$content = htmlspecialchars($content);

			if ( $polaczenie->query("INSERT INTO answer VALUES (NULL, '$topic_id', '$id_user', '$content')")) 
			{
				$polaczenie->query("UPDATE topics SET ile_odp = ile_odp + 1 WHERE id=$topic_id");

				$_SESSION['add_answer_info'] = "<span style='color:green'>Odpowiedź została dodana</span>";
				header("Location: post/index.php?id=".$topic_id);
			}

			else
			{
				throw new Exception($polaczenie->error);
			}


			$polaczenie->close();
		}

	}

	catch(Exception $e)
	{
		$_SESSION['add_answer_info'] = "<span style='color:green'>Wystąpił błąd serwera w trakcie dodawania odpowiedzi. Spróbuj ponownie</span>";
		header("Location: post/index.php?id=".$topic_id);
	}


?>