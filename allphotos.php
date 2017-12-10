<?php
	require("functions.php");
	
	$limit = 20;
	
	$fileName;
	//kui pole sisse logitud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy(); //lõpetab sessiooni
		header("Location: login.php");
	}
	
	//piltide lehekülgede kontroll
	if(!isset($imageCount)){
		$imageCount = findNumberOfSharedImages();
	}
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		header("Location: ?page=1");
	}
	
	if($imageCount <= ($_GET["page"] - 1) * $limit){
		if($imageCount == 0){
			//header("Location: ?page=1");//tekitas edasisuunaiste tsükli
		} else {
			header("Location: ?page=" .ceil($imageCount / $limit));
		}
	}
	
	/*
	
	if....kui valitud mingi pilt
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id FROM grphotos2 WHERE filename=? ");
		$stmt->bind_param("i", $fileName);
		$stmt->bind_result($photoId);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	*/
	$photoId = 10;
	
	if (!empty($_POST["comment"]) and isset($_POST["submit"])){
		
		
		$insertComment = $_POST["comment"];
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO grcomments (userid, photo_id, text) VALUES (?, ?, ?)");
		$stmt->bind_param("iis", $_SESSION["userId"], $photoId, $insertComment);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
		
	}
	
	
	
	
	
	require("header.php");
?>

	<link rel="stylesheet" type="text/css" href="style/modal.css">
	<link rel="stylesheet" type="text/css" href="style/general.css">
	<script type="text/javascript" src="javascript/modalImage.js" defer></script>
</head>
<body class="bg-dark">
	<div class="container-fluid text-white">
	<?php
		require("top_part.php");
	?>
	<div class="row">
	<div class="col-sm-2">
	<p><a href="?logout=1">Logi välja</a></p>
	<p><a href="main.php">Pealeht</a></p>
	</div>
	<div class="col-sm-8">
	<h2>Kõikide kasutajate avaldatavad fotod</h2>
	
	<!-- The Modal W3schools eeskujul-->
	<div id="myModal" class="modal">
		<!-- The Close Button -->
		<span class="close">&times;</span>
		<!-- Modal Content (The Image) -->
		<img class="modal-content" src="../graphics/hmv_logo.png" alt="" id="modalImage">
		<!-- Modal Caption (Image Text) -->
		<div id="caption"></div>
	</div>
	
	<div id="allThumbnails">
	<table class="pageLinks">
	<tr>
	<td class="half leftLink">
	<?php
		if($_GET["page"] > 1){
			echo '<a href="?page=' .($_GET["page"] - 1) .'">Eelmised pildid</a>';
		}
		
	?>
	</td>
	<td class="half rightLink">
	<?php
		if($imageCount > $_GET["page"] * $limit){
			echo '<a href="?page=' .($_GET["page"] + 1) .'">Järgmised pildid</a>';
		}
		
	?>
	</td>
	</tr>
	</table>
	<?php
		showSharedThumbnailsPage($_GET["page"], $limit);
	?>
	

	
	</div>
	</div>
	</div>
	</div>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<input type="text" name = "comment" id = "comment">
	<input type="submit" name = "submit" value = "Salvesta" id = "commentSubmit">
	
	</form>
	<?php
	
		
		$date = "";
		$comments = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT text, created FROM grcomments WHERE photo_id = ? ORDER BY created DESC LIMIT 5");
		$stmt->bind_param("i", $photoId);
		$stmt->bind_result($comments, $date);
		
		$stmt->execute();
		while($stmt->fetch()) {
			echo $comments ." - " .$date ."<br>" ."<br>";
		}
		
		$stmt->close();
		$mysqli->close();
	?>
	<button onclick="openModal()" id = "comment1">Try it</button>
</body>

</html>



	<?php
		require("footer.php");
	?>
	


