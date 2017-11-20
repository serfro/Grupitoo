<?php
	require("functions.php");
		
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
	
	/*
	while($stmt->fetch()){
		
	}
	*/
	require("../../usersinfotable.php");
	
	require("header.php");
?>

	<h1>Alexander Lawrence</h1>
	<p>See veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda mingisugust t�siseltv�etavat sisu.</p>
	<p><a href="?logout=1">Logi v�lja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>K�ik s�steemi kasutajad</h2>
	<?php echo createUsersTable(); ?>
	<hr>
	<h3>N�idistabel oli selline</h3>
	<table border="1" style="border: 1px solid black; border-collapse: collapse">
	<tr>
		<th>Eesnimi</th><th>perekonnanimi</th><th>e-posti aadress</th>
	</tr>
	<tr>
		<td>Juku</td><td>Porgand</td><td>juku.porgand@aed.ee</td>
	</tr>
	<tr>
		<td>Mari</td><td>Karus</td><td>mari.karus@aed.ee</td>
	</tr>
	
	</table>
<?php
	require("footer.php");
?>