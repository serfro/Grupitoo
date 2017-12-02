<?php

//Store the upload form
$UploadForm = " <form id='idForm' action='upload.php' method='post' enctype='multipart/form-data'>
                    <input type='file' name='image'/><br/><br/>
                    <input id='BTN' type='submit' value='Upload'/><br/><br/>
            </form>";
//if logged in show the upload form
//if($userid && $username){
//    echo $UploadForm;
	
// Connect to database
$database = "if17_lawralex";
$con = new mysqli("localhost", "if17", "if17", "if17_lawralex");
// Check connection
if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

//file properties
if(isset($_FILES['image'])){
    $file = $_FILES['image']['tmp_name'];
}

//if image selected
if(isset($file) && $file != ""){
    $image = mysqli_real_escape_string($con,file_get_contents($_FILES['image']['tmp_name']));
    $image_name = addslashes($_FILES['image']['id']);
    $image_size = getimagesize($_FILES['image']['tmp_name']);

    if($image_size == FALSE){
        echo "That's not an image!";
        header( "refresh:2;url=upload.php" );
    }
    else{
        $qry = mysqli_query($con,"SELECT * FROM grphotos WHERE id='$image_name'");
        $Nrows = $qry->num_rows;
        if( $Nrows == 0){
            if(!$insert = mysqli_query($con,"INSERT INTO grphotos VALUES ('','$image_name','$username','$image')")){
            echo "We had problems uploading your file!";
            header( "refresh:2;url=upload.php" );
        }
        else{
            echo "Image $image_name uploaded!";
            header( "refresh:2;url=upload.php" );
        }
    }
    else{
        echo "There is already an image uploaded with the name $image_name<br/>";
    }
}
}   
else{
    echo "Please select an image";
}
mysqli_close($con);

//else{
//    echo "You have to be logged in to upload!";
//}
$con = new mysqli("localhost", "if17", "if17", "if17_lawralex");
$query = mysqli_query($con,"SELECT id FROM grphotos");
while($row = mysqli_fetch_assoc($query))
{
    $IDstore = $row['id'];
    echo "<img src='../pics/?id=".$IDstore."'/>";
}


$con = new mysqli("localhost", "if17", "if17", "if17_lawralex");
if(isset($_GET['id']))
{
    $id = mysqli_real_escape_string($con,$_GET['id']);
    $query = mysqli_query($con,"SELECT * FROM grphotos WHERE id=$id");
    while($row = mysqli_fetch_assoc($query))
    {
        $imageData = $row['image'];
    }
    header("content-type:image/jpeg");
    echo $imageData;
}
else
{
    echo "Error!";
}

?>
<img src="getImage.php?$id"/>