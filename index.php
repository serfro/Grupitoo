<?php
	require("functions.php");
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib v‰lja
	if (isset($_GET["logout"])){
		//lıpetame sessiooni
		session_destroy();
		header("Location: login.php");
		exit();
	}
	$dirToRead = "../pics/";
	//kuna tahan ainult pildifaile, siis filtreerin
	$picFileTypes = ["jpg", "jpeg", "png", "gif"];
	$picFiles = [];
	//$allFiles = scandir($dirToRead);
	//loen kataloogi ja viskan kaks esimest massiivi liiget (. ja ..) v‰lja
	$allFiles = array_slice(scandir($dirToRead),2);
	//var_dump($allFiles);
	
	//ts¸kkel, mis tˆˆtab ainult massiividega
	foreach ($allFiles as $file){
		$fileType = pathinfo($file, PATHINFO_EXTENSION);
		//kas see t¸¸p on lubatud nimekirjas
		if (in_array($fileType, $picFileTypes) == true){
			array_push($picFiles, $file);
			//$picFiles[] = $file;
		}
	}//foreach lıppeb
	//var_dump($picFiles);
	
	//mitu pilti on?
	$fileCount = count($picFiles);
	$picNumber = mt_rand(0, $fileCount - 1);
	$picToShow = $picFiles[$picNumber];
	require("header.php");
?>


<body>
	<?php require ("top_part.php");?>
	<p><a href="?logout=1">Logi v√§lja</a>!</p>
	<p><a href="photoupload.php">Fotode √ºleslaadimine</a></p>
	<p><a href="photos.php">Kasutajate fotod ja kommentaarid</a></p>
	
<?php
	require("footer.php");
?>
