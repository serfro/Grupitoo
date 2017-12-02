<?php
	require("functions.php");
	
	$limit = 20;
	
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
		$imageCount = findNumberOfImages();
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
	<h2>Fotod</h2>
	
	<!-- The Modal W3schools eeskujul-->
	<div id="myModal" class="modal">
		<!-- The Close Button -->
		<span class="close">&times;</span>
		<!-- Modal Content (The Image) -->
		<img class="modal-content" src="../graphics/hmv_safe.jpg" alt="" id="modalImage">
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
		showThumbnailsPage($_GET["page"], $limit);
	?>
	</div>
	</div>
	</div>
	<?php
		require("footer.php");
	?>
	
