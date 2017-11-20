<?php
	require("functions.php");
	require("ideaeditfunctions.php");
	
	$notice = "";
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib v�lja
	if (isset($_GET["logout"])){
		//l�petame sessiooni
		session_destroy();
		header("Location: login.php");
	}
	
	
	//kas uuendatakse
	if (isset($_POST["update"])){
		echo "hakkab uuendama!";
		echo $_POST["id"];
		updateIdea($_POST["id"], test_input($_POST["idea"]), $_POST["ideaColor"]);
		header("Location: usersideas.php");
		exit();
	}
	
	
	//Loen muudetava m�tte
	if(isset($_GET["id"])){
		$idea = getSingleIdeaData($_GET["id"]);
	} else {
		header("Location: usersideas.php");
	}
	
	require("header.php");
?>


	<h1>Andrus Rinde</h1>
	<p>See veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda mingisugust t�siseltv�etavat sisu.</p>
	<p><a href="?logout=1">Logi v�lja</a>!</p>
	<p><a href="usersideas.php">Tagasi m�tete lehele</a></p>
	<hr>
	<h2>Hea m�tte toimetamine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		<label>Hea m�te: </label>
		<textarea name="idea"><?php echo $idea->text; ?></textarea>
		<br>
		<label>m�ttega seostuv v�rv: </label>
		<input name="ideaColor" type="color" value="<?php echo $idea->color; ?>">
		<br>
		<input name="update" type="submit" value="Salvesta muudatused">
		<span><?php echo $notice; ?></span>
	
	</form>
<?php
	require("footer.php");
?>
