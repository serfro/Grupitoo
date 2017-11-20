<?php
	require("functions.php");
	$notice = "";
	$allIdeas = "";
	//require = new photoupload
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib välja
	if (isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("Location: login.php");
	}
	
	
	require("classes/Photoupload.class.php");
	
	$target_dir = "../pics/";
	$target_file;
	$uploadOk = 1;
	$imageFileType;
	$maxWidth = 600;
	$maxHeight = 400;
	$marginRight = 0;
	$marginBottom = 0;
	$comment = "";
	
	//Kas on pildi failitüüp
	if(isset($_POST["submit"])) {
		if(!empty($_FILES["fileToUpload"] ["name"]))
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);	
			//$target_file = $target_dir .pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .(microtime(1) * 10000) ."." .$imageFileType;
			$target_file = "hmv_" .(microtime(1) * 10000) ."." .$imageFileType;
				
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
					$notice .= "Fail on pilt - " . $check["mime"] . ". ";
					$uploadOk = 1;
		} else {
					$notice .= "See pole pildifail. ";
					$uploadOk = 0;
		}
		
		//Kas selline pilt on juba üles laetud
		if (file_exists($target_file)) {
			$notice .= "Kahjuks on selle nimega pilt juba olemas. ";
			$uploadOk = 0;
		}
		//Piirame faili suuruse
		/*if ($_FILES["fileToUpload"]["size"] > 1000000) {
			$notice .= "Pilt on liiga suur! ";
			$uploadOk = 0;
		}*/
		
		//Piirame failitüüpe
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$notice .= "Vabandust, vaid JPG, JPEG, PNG ja GIF failid on lubatud! ";
			$uploadOk = 0;
		}
		
		
		if ($uploadOk == 0) {
			$notice .= "Vabandust, pilti ei laetud üles! ";
		} else {
			
			
			
			$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
			$myPhoto->readExif();
			$myPhoto->resizeImage($maxWidth, $maxHeight);
			//$myPhoto->addWatermark();
			//$myPhoto->addTextWatermark($myPhoto->exifToImage);
			//$myPhoto->addTextWatermark("hmv_foto");
			$myPhoto->savePhoto($target_dir, $target_file);
			$myPhoto->clearImages();
			unset($myPhoto);
	}
	} else {
		$notice = "Palun valige kõigepelt pildifail";
	}
		

	
	require("header.php");
?>
<body>
	<h1>Lae pilt</h1>
	<p></p>
	<p><a href="?logout=1">Logi välja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Foto üleslaadimine</h2>
	<form action="photoupload.php" method="post" enctype="multipart/form-data">
		<label>Valige pildifail:</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Lae üles" name="submit" id="submitPhoto"><span id="fileSizeError"></span>
		<br>
		<!--<label>Lisage kommentaar: </label>
		<input type="text" name="comment" id="comment">
		-->
	</form>
	
	<span><?php echo $notice; ?></span>
	
	<?php
	echo '<script type="text/javascript" src="javascript/checkFileSize.js"></script>';
	require("footer.php");
	
	?>