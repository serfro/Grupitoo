<<<<<<< HEAD
<?php
	require("functions.php");
	$notice = "";
	
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
	
	//Algab foto laadimise osa
	$target_dir = "../../pics/";
	$target_file;
	$uploadOk = 1;
	$imageFileType;
	$maxWidth = 600;
	$maxHeight = 400;
	$marginBottom = 10;
	$marginRight = 10;
	
	//Kas on pildi failitüüp
	if(isset($_POST["submit"])) {
		
		//kas mingi fail valiti
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//tekitame failinime koos ajatempliga
			$target_file = $target_dir .pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .(microtime(1) * 10000) ."." .$imageFileType;
			//$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			
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
			if ($_FILES["fileToUpload"]["size"] > 1000000) {
				$notice .= "Pilt on liiga suur! ";
				$uploadOk = 0;
			}
			
			//Piirame failitüüpe
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
				$uploadOk = 0;
			}
			
			//Kas saab laadida?
			/*if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {		
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$notice .= "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
				} else {
					$notice .= "Vabandust, üleslaadimisel tekkis tõrge! ";
				}
			}*/
			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {
				
				//loeme EXIF infot, millal pilt tehti
				@$exif = exif_read_data($_FILES["fileToUpload"]["tmp_name"], "ANY_TAG", 0, true);
				//var_dump($exif);
				if(!empty($exif["DateTimeOriginal"])){
					$textToImage = "Pilt tehti: " .$exif["DateTimeOriginal"];
				} else {
					$textToImage = "Pildistamise aeg teadmata!";
				}
				
				//lähtudes failitüübist, loon sobiva pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				//suuruse muutmine
				//küsime originaalsuurust
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				$sizeRatio = 1;
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / $maxWidth;
				} else {
					$sizeRatio = $imageHeight / $maxHeight;
				}
				$myImage = resize_image($myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));
				
				//vesimärgi lisamine
				$stamp = imagecreatefrompng("../../graphics/hmv_logo.png");
				$stampWidth = imagesx($stamp);
				$stampHeight = imagesy($stamp);
				$stampPosX = round($imageWidth / $sizeRatio) - $stampWidth - $marginRight;
				$stampPosY = round($imageHeight / $sizeRatio) - $stampHeight - $marginBottom;
				imageCopy($myImage, $stamp, $stampPosX, $stampPosY, 0, 0, $stampWidth, $stampHeight);
				
				//lisame ka teksti vesimärgina
				//imagecolorallocate
				$textColor = imagecolorallocatealpha($myImage, 150, 150, 150, 50);
				//RGBA alpha 0 -127
				imagettftext($myImage, 20, 0, 10, 25, $textColor, "../../graphics/ARIAL.TTF", $textToImage);
				
				//salvestame pildi
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 95)){
						$notice = "Fail: " . basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
					} else {
						$notice .= "Vabandust, üleslaadimisel tekkis tõrge! ";
					}
				}
				if($imageFileType == "png"){
					if(imagepng($myImage, $target_file, 95)){
						$notice = "Fail: " . basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
					} else {
						$notice .= "Vabandust, üleslaadimisel tekkis tõrge! ";
					}
				}
				if($imageFileType == "gif"){
					if(imagegif($myImage, $target_file, 95)){
						$notice = "Fail: " . basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
					} else {
						$notice .= "Vabandust, üleslaadimisel tekkis tõrge! ";
					}
				}
				
				//mälu vabastamine
				imagedestroy($myImage);
				imagedestroy($myTempImage);

				}
                                

			}
		
		} else {
			$notice = "Palun valige kõigepealt pildifail!";
		} //kas üldse mõni fail valiti, lõppeb
	}//kas vajutati submit nuppu, lõppeb
	function resize_image($image, $origW, $origH, $w, $h){
		$dst = imagecreatetruecolor($w, $h);
		imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
		return $dst;
	}
	require("header.php");
?>


	<h1>Grupitöö</h1>
	<p>See veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p><a href="?logout=1">Logi välja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Foto üleslaadimine</h2>
	<form action="photoupload.php" method="post" enctype="multipart/form-data">
		<label>Valige pildifail:</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Lae üles" name="submit">
	</form>
	
	<span><?php echo $notice; ?></span>
<?php
	require("footer.php");
?>
=======
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
	
	//kui logib vÃ¤lja
	if (isset($_GET["logout"])){
		//lÃµpetame sessiooni
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
	
	
	//Kas on pildi failitÃ¼Ã¼p
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
		
		//Kas selline pilt on juba Ã¼les laetud
		if (file_exists($target_file)) {
			$notice .= "Kahjuks on selle nimega pilt juba olemas. ";
			$uploadOk = 0;
		}
		//Piirame faili suuruse
		/*if ($_FILES["fileToUpload"]["size"] > 1000000) {
			$notice .= "Pilt on liiga suur! ";
			$uploadOk = 0;
		}*/
		
		//Piirame failitÃ¼Ã¼pe
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$notice .= "Vabandust, vaid JPG, JPEG, PNG ja GIF failid on lubatud! ";
			$uploadOk = 0;
		}
		
		
		if ($uploadOk == 0) {
			$notice .= "Vabandust, pilti ei laetud Ã¼les! ";
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
		$notice = "Palun valige kÃµigepelt pildifail";
	}
		

	
	require("header.php");
?>
<body>
	<h1>Lae pilt</h1>
	<p></p>
	<p><a href="?logout=1">Logi vÃ¤lja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Foto Ã¼leslaadimine</h2>
	<form action="photoupload.php" method="post" enctype="multipart/form-data">
		<label>Valige pildifail:</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Lae Ã¼les" name="submit" id="submitPhoto"><span id="fileSizeError"></span>
		
	</form>
	
	<span><?php echo $notice; ?></span>
	
	<?php
	echo '<script type="text/javascript" src="javascript/checkFileSize.js"></script>';
	require("footer.php");
	
	?>
>>>>>>> 3e60ce4e81aadf0b8537d890030b8853c3252a4b
