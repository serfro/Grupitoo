<?php
	require("functions.php");
	require("classes/Photoupload.class.php");
	$notice = "";
	
	/*$joke = new Photoupload("Väga salajane värk!");
	echo $joke->testPublic;
	echo $joke->testPrivate;*/
	
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
	$target_dir = "../pics/";
	$thumbs_dir = "../thumbnails/";
	$target_file = "";
	$thumb_file = "";
	$uploadOk = 1;
	$imageFileType = "";
	$maxWidth = 600;
	$thumbsize = 100;
	$maxHeight = 400;
	$marginVer = 10;
	$marginHor = 10;
	
	//Kas vajutati üleslaadimise nuppu
	if(isset($_POST["submit"])) {
		
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			$timeStamp = microtime(1) *10000;
			//$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .$timeStamp ."." .$imageFileType;
			$target_file = "hmv_" .$timeStamp ."." .$imageFileType;
			$thumb_file = "hmv_" .$timeStamp .".jpg";
		
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
				$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
				$uploadOk = 0;
			}
			
			//Kas saab laadida?
			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {		
								
				//kasutame klassi
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->readExif();
				$myPhoto->resizeImage($maxWidth, $maxHeight);
				//$myPhoto->addWatermark($marginHor, $marginVer);
				//$myPhoto->addTextWatermark($myPhoto->exifToImage);
				//$myPhoto->addTextWatermark("Heade mõtete veeb");
				$notice .= $myPhoto->savePhoto($target_dir, $target_file);
				//$myPhoto->saveOriginal(kataloog, failinimi);
				$notice .= $myPhoto->createThumbnail($thumbs_dir, $thumb_file, $thumbsize, $thumbsize);
				$myPhoto->clearImages();
				unset($myPhoto);
				
				//lisame andmebaasi
				if(isset($_POST["altText"]) and !empty($_POST["altText"])){
					$alt = $_POST["altText"];
				} else {
					$alt = "Foto";
				}
				addPhotoData($target_file, $thumb_file, $alt, $_POST["privacy"]);
				
			}
		
		} else {
			$notice = "Palun valige kõigepealt pildifail!";
		}//kas faili nimi on olemas lõppeb
	}//kas üles laadida lõppeb
	
	require("header.php");
?>
<script type="text/javascript" src="javascript/checkFileSize.js" defer></script>
</head>
<body class="bg-dark">
	<div class="container-fluid text-white">
	<?php
		require("top_part.php");
	?>
	<div class="row">
	<div class="col-sm-2">
	<p><a href="?logout=1">Logi välja</a></p>
	<p><a href="index.php">Pealeht</a></p>
	</div>
	<div class="col-sm-8">
	<h2>Foto üleslaadimine</h2>
	<form action="photoupload.php" method="post" enctype="multipart/form-data">
		<label>Valige pildifail:&nbsp;</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<br>
		<label>Alt tekst: &nbsp;</label><input type="text" name="altText">
		<br>
		<input type="radio" name="privacy" value="1"><label>&nbsp; Avalik &nbsp;</label>
		<input type="radio" name="privacy" value="2"><label>&nbsp; Registreeritud kasutajatele &nbsp;</label>
		<input type="radio" name="privacy" value="3" checked><label>&nbsp; Isiklik </label>
		<br>
		<input type="submit" value="Lae üles" name="submit" id="photoSubmit"><span id="fileSizeError"></span>
	</form>
	
	<span id="resultNotice"><?php echo $notice; ?></span>
	</div>
	</div>
<?php
	require("footer.php");
?>