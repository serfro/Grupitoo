<?php
	require("functions.php");
	$notice = "";
	$allIdeas = "";
	
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
	
	//kui soovitakse ideed salvestada
	if(isset($_POST["ideaBtn"])){
		
		if(isset($_POST["idea"]) and isset($_POST["ideaColor"]) and !empty($_POST["idea"]) and !empty($_POST["ideaColor"])){
			$myIdea = test_input($_POST["idea"]);
			$notice = saveIdea($myIdea, $_POST["ideaColor"]);
		}
	}
	
	$allIdeas = readAllIdeas();
	
	require("header.php");
?>

	<h1>Alexander Lawrence</h1>
	<p>See veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda mingisugust t�siseltv�etavat sisu.</p>
	<p><a href="?logout=1">Logi v�lja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Lisa uus m�te</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>P�eva esimene m�te: </label>
		<input name="idea" type="text">
		<br>
		<label>m�ttega seostuv v�rv: </label>
		<input name="ideaColor" type="color">
		<br>
		<input name="ideaBtn" type="submit" value="Salvesta">
		<span><?php echo $notice; ?></span>
	
	</form>
	<hr>
	<h2>Senised m�tted</h2>
	<div style="width: 40%">
		<?php echo $allIdeas; ?>
	</div>
<?php
	require("footer.php");
?>
