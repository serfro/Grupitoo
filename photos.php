<?php
require("functions.php");
require_once("classes/GetPost.php");
$commentValue = "";
if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//väljalogimine
if(isset($_GET["logout"])){
		session_destroy(); //lõpetab sessiooni
		header("Location: login.php");
	}


//if(isset($_POST['submit'])){ // if user submits form
//	 echo "1234";
//     echo GetPost::get('comment');
//}

if (!empty($_POST["comment"]) and isset($_POST["submit"])){
	$insertComment = $_POST["comment"];
	 print_r($_POST);
	/*foreach($_POST as $id => $content) { // Most people refer to $key => $value
		$photoId = $id;
		echo $photoId;
	}*/
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO grcomments (userid, photo_id, text) VALUES (?, ?, ?)");
	$stmt->bind_param("iss", $_SESSION["userId"], $photoId, $insertComment);
	$stmt->execute();
	$stmt->close();
	$mysqli->close();
}
	
require ("header.php");
?>
	<link rel="stylesheet" type="text/css" href="style/modal.css">
	<link rel="stylesheet" type="text/css" href="style/general.css">
	</head>
	<body class="bg-dark">
	<?php
		require("top_part.php");
	?>

	<div class="col-sm-2">
	<p><a href="?logout=1">Logi välja</a></p>
	<p><a href="index.php">Pealeht</a></p>
	</div>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<?php
		showCommentsAndPhotos()
	?>


<?php
require("footer.php");
?>