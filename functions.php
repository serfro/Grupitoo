<?php
	require("../../config.php");
	$database = "if17_lawralex";
	$photo_dir = "../pics/";
	$thumb_dir = "../thumbnails/";
	//alustan sessiooni
	session_start();
	
	//sisselogimise funktsioon
	function signIn($email, $password){
		$notice = "";
		//ühendus serveriga
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, password FROM vpusers WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $firstnameFromDb, $lastnameFromDb, $emailFromDb, $passwordFromDb);
		$stmt->execute();
		
		//kontrollime vastavust
		if ($stmt->fetch()){
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb){
				$notice = "Logisite sisse!";
				
				//Määran sessiooni muutujad
				$_SESSION["userId"] = $id;
				$_SESSION["firstname"] = $firstnameFromDb;
				$_SESSION["lastname"] = $lastnameFromDb;
				$_SESSION["userEmail"] = $emailFromDb;
				
				//liigume edasi pealehele (main.php)
				header("Location: main.php");
				exit();
			} else {
				$notice = "Vale salasõna!";
			}
		} else {
			$notice = 'Sellise kasutajatunnusega "' .$email .'" pole registreeritud!';
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	//kasutaja salvestamise funktsioon
	function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword){
		//loome andmebaasiühenduse
		
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthday, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		//s - string
		//i - integer
		//d - decimal
		$stmt->bind_param("sssiss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
		//$stmt->execute();
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			echo "\n Tekkis viga : " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	//mõtete salvestamine
	function saveIdea($idea, $color){
		//echo $color;
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpuserideas (userid, idea, ideacolor) VALUES (?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("iss", $_SESSION["userId"], $idea, $color);
		if($stmt->execute()){
			$notice = "Mõte on salvestatud!";
		} else {
			$notice = "Mõtte salvestamisel tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	//kõikide ideede lugemise funktsioon
	function readAllIdeas(){
		$ideasHTML = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $mysqli->prepare("SELECT idea, ideaColor FROM vpuserideas WHERE userid = ?");
		$stmt = $mysqli->prepare("SELECT id, idea, ideaColor FROM vpuserideas WHERE userid = ? ORDER BY id DESC");
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($ideaId, $idea, $color);
		$stmt->execute();
		//$result = array();//?
		while ($stmt->fetch()){
			$ideasHTML .= '<p style="background-color: ' .$color .'">' .$idea .' | <a href="ideaedit.php?id=' .$ideaId .'">Toimeta</a>' ."</p> \n";
			//link: <a href="ideaedit.php?id=4"> Toimeta</a>
		}
		$stmt->close();
		$mysqli->close();
		return $ideasHTML;
	}
	
	//uusima idee lugemine
	function latestIdea(){
		//$ideaHTML = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT idea FROM vpuserideas WHERE id = (SELECT MAX(id) FROM vpuserideas)");
		//$stmt->bind_param("i", $last_id);
		//echo "Viga: " .$mysqli->error;
		$stmt->bind_result($idea);
		/*if($stmt->execute()){
			echo "Hea" .$idea;
			//$ideaHTML .= $idea;
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}*/
		$stmt->execute();
		$stmt->fetch();//nüüd jääb meelde, kui fetch() ei tee, andmeid ei saa!
		$stmt->close();
		$mysqli->close();
		return $idea;
	}
	
	//sisestuse kontrollimise funktsioon
	function test_input($data){
		$data = trim($data);//ebavajalikud tühiku jms eemaldada
		$data = stripslashes($data);//kaldkriipsud jms eemaldada
		$data = htmlspecialchars($data);//keelatud sümbolid
		return $data;
	}
	
	
	
	function readAllComments($fcomments, $fphotoId){
		$fcomments = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT comment FROM grcomments WHERE photoid = ? ORDER BY id DESC");
		$stmt->bind_param("i", $fphotoId);
		$stmt->bind_result($fcomments);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		$mysqli->close();
		return $fcomments;
	}
		function latestPicture($privacy){
		//$privacy = 1;
		$html = "<p>Varskeid avalikke pilte pole! Vabandame!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM grphotos2 WHERE id=(SELECT MAX(id) FROM grphotos2 WHERE  privacy<=?)");
		echo $mysqli->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filename, $thumbnail, $alt);
		$stmt->execute();
		echo $stmt->error;
		if($stmt->fetch()){
			
			$html = '<img src="' .$GLOBALS["photo_dir"] .$filename .'" alt="' .$alt .'" class="rounded">';
		}
		$stmt->close();
		$mysqli->close();
		return $html;
	}
	
	function showAllThumbnails(){
		$html = "<p>Te pole ise uhtki pilti ules laadinud!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM grphotos2 WHERE userid = ?");
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($filename, $thumbnail, $alt);
		$stmt->execute();
		//koik pisipildid
		if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		while ($stmt->fetch()){
			$html .= "\t" .'<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		
		$stmt->close();
		$mysqli->close();
		echo $html;
	}
	
	function showThumbnailsPage($page, $limit){
		$skip = ($page - 1) * $limit;
		$html = "<p>Te pole ise uhtki pilti ules laadinud!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM vpphotos WHERE userid = ?");
		$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM grphotos2 WHERE userid = ? ORDER BY id DESC LIMIT " .$skip ."," .$limit);
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($filename, $thumbnail, $alt);
		
		$stmt->execute();
		
		
		
		//koik pisipildid
		if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		while ($stmt->fetch()){
			$html .= "\t" .'<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		
		$stmt->close();
		$mysqli->close();
		echo $html;
	}
	
	function showSharedThumbnailsPage($page, $limit){
		$skip = ($page - 1) * $limit;
		$html = "<p>Te pole ise uhtki pilti ules laadinud!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM vpphotos WHERE userid = ?");
		//$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM vpphotos WHERE privacy < ? ORDER BY id DESC LIMIT " .$skip ."," .$limit);
		$stmt = $mysqli->prepare("SELECT firstname, lastname, filename, thumbnail, alt FROM grphotos2, vpusers WHERE grphotos2.userid = vpusers.id AND grphotos2.privacy < ? ORDER BY grphotos2.id DESC LIMIT " .$skip ."," .$limit);
		$privacyVal = 3;
		$stmt->bind_param("i", $privacyVal);
		//$stmt->bind_result($filename, $thumbnail, $alt);
		$stmt->bind_result($firstname, $lastname, $filename, $thumbnail, $alt);
		
		$stmt->execute();
				
		//koik pisipildid
		/*if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}*/
		$html = "\n";
		while ($stmt->fetch()){
			$html .= "\t" .'<div class="thumbGallery">' ."\n";
			$html .= "\t \t" .'<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs" title="' .$firstname ." " .$lastname .'">' ."\n";
			$html .= "\t \t <p>" .$firstname ." " .$lastname ."</p> \n";
			$html .= "\t </div> \n";
		}
		
		$stmt->close();
		$mysqli->close();
		echo $html;
	}
	function showComments(){
		
	}
	
	function findNumberOfImages(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT COUNT(*) FROM `grphotos` WHERE userid = ?");
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($imageCount);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		$mysqli->close();
		return $imageCount;
	}
	
	function findNumberOfSharedImages(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT COUNT(*) FROM `grphotos2` WHERE privacy < ?");
		$privacyVal = 3;
		$stmt->bind_param("i", $privacyVal);
		$stmt->bind_result($imageCount);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		$mysqli->close();
		return $imageCount;
	}
	
	function addPhotoData($filename, $thumbname, $alt, $privacy){
		//echo $GLOBALS["serverHost"];
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO grphotos2 (userid, filename, thumbnail, alt, privacy) VALUES (?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("isssi", $_SESSION["userId"], $filename, $thumbname, $alt, $privacy);
		//$stmt->execute();
		if ($stmt->execute()){
			$GLOBALS["notice"] .= "Foto andmete lisamine andmebaasi onnestus! ";
		} else {
			$GLOBALS["notice"] .= "Foto andmete lisamine andmebaasi ebaonnestus! ";
		}
		$stmt->close();
		$mysqli->close();
	}
	/*function selectPhoto(){
		$photos = ""
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename FROM grphotos2 DESC");
		$stmt->bind_result($fcomments);
		$stmt->execute();
		while ($stmt->fetch()){
			$ .= '<p style="background-color: ' .$color .'">' .$idea .' | <a href="ideaedit.php?id=' .$ideaId .'">Toimeta</a>' ."</p> \n";
			//link: <a href="ideaedit.php?id=4"> Toimeta</a>
		$stmt->close();
		$mysqli->close();
	}*/
	/*
	$x = 5;
	$y = 6;
	echo ($x + $y);
	addValues();
	
	function addValues(){
		$z = $GLOBALS["x"] + $GLOBALS["y"];
		echo "Summa on: " .$z;
		$a = 3;
		$b = 4;
		echo "Teine summa on: " .($a + $b);
	}
	echo "Kolmas summa on: " .($a + $b);
	*/
	
?>