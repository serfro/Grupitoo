<?php
require ("functions.php");
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
	
	
	$image;
     $dirToRead = selectPhotos();
     
	
       
	
	
require("header.php");
?>
<body>
	<?php
	
	
	
	
	/*
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
			//echo $image."<br /><br />";
            echo '<img src="'.$image .'" alt="" />'."<br /><br />";
			$photoId = 
			?>
			<h4>Kommentaarid:</h4>
			<div style="width: 40%">
			<?php
				//$comments = readAllComments($comments, $photoId);
				//echo $comments; 
			?>
            <label>Sinu kommentaar: </label>
			</br></br>
			<textarea name="comment" type="text"></textarea>
			</br></br>
			<input type="submit" value="Lisa" name="submit" id="submitComment">
			</br></br></br>
			</div>
			<?php
			
			
            } else {
                continue;
            }
		}
	?>
	

*/

?>
<?php
require("footer.php");
?>