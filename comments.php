<?php

$dirToRead = "../pics/";
/*
	//kuna tahan ainult pildifaile, siis filtreerin
	$picFileTypes = ["jpg", "jpeg", "png", "gif"];
	$picFiles = [];
	$allFiles = scandir($dirToRead);
	//loen kataloogi ja viskan kaks esimest massiivi liiget (. ja ..) vдlja
	$allFiles = array_slice(scandir($dirToRead),2);
	var_dump($allFiles);
	
	//tsьkkel, mis tццtab ainult massiividega
	foreach ($allFiles as $file){
		$fileType = pathinfo($file, PATHINFO_EXTENSION);
		//kas see tььp on lubatud nimekirjas
		if (in_array($fileType, $picFileTypes) == true){
			array_push($picFiles, $file);
			//$picFiles[] = $file;
		}
	}//foreach lхppeb
	var_dump($picFiles);
	
	//mitu pilti on?
	//$fileCount = count($picFiles);
	
	$picToShow = $picFiles[];
	*/
	
	$comments;
	$image;
     $dirToRead = glob("../pics/*.*");
     for ($i=0; $i<count($dirToRead); $i++){
        $image = $dirToRead[$i];
        $supported_file = array(
                'gif',
                'jpg',
                'jpeg',
                'png'
         );

         $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
         if (in_array($ext, $supported_file)) {
            echo '<img src="'.$image .'" alt="" />'."<br /><br />";
			echo $image."<br />";
            
            } else {
                continue;
            }
		}
       
	   
	
require("header.php");
?>
<body>
	





<?php
require("footer.php");
?>