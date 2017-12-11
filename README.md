# Grupitoo
Projekti nimi: Piltide jagamis ja kommentaarimis leht
Rühmaliikmete nimed: Alexander Lawrence, Sergei Frolov
Eesmärk: Tahetakse oma pilte jagada anonüümselt. Iga pildi kohta saab anda oma arvamust.
Sihtrühm: Noored, kes saavad anonüümselt jagada oma arvamusi.
Funktsionaalususe loetelu prioriteedi: lood kasutaja lodi sisse, laed pildi üles ja/või saad pilte kommenteerida


Fotode ja kommentaaride väljastamise kood

	function showCommentsAndPhotos(){
		$photoAndComments = "<p>Te pole ise uhtki pilti ules laadinud!</p>";
		$comments = "<p>kommentaare pole</p>";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, id, alt, userid FROM grphotos2 ORDER BY created DESC LIMIT 5");
		$stmt->bind_result($fileName, $photoId, $photoAlt, $photoUserId);
		$stmt->execute();
		
		$photoAndComments = "\n";
		$stmt->execute();
		while($stmt->fetch()){
			$photoAndComments .= "\t" .'<div class="photoGallery">' ."\n";
			$photoAndComments .= "\t \t" .'<img src="' .$GLOBALS["photo_dir"] .$fileName.'" id="' .'" class="photos" title="' .'">' ."\n";
			$photoAndComments .= "\t \t <h2>" .$photoAlt ."</h2> \n";
			$photoAndComments .= "\t \t <p>" .$comments ."</p> \n";
			$photoAndComments .= "\t \t <h3>Lisa oma kommentaar: </h3>\n";
			$photoAndComments .= "<textarea rows='4' cols='50' id = comment name = comment></textarea><br>";
			$photoAndComments .= "<input type='submit' name = 'submit' value = 'Salvesta' id = '" .$fileName ."'> <br> <br> ";
			$photoAndComments .= "\t </div> \n";
		}
		$stmt->close();
		$mysqli->close();
    echo $photoAndComments;
    }
