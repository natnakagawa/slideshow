<!DOCTYPE html>
<html>
<head>
	<title>Slideshow</title>
	<link rel="stylesheet" type="text/css" href="slideshow.css">
</head>
<body>
	<head>
		<a href="welcome.php"><h1>Slideshow</h1></a>
		<nav>
			<a href="index.html">Show my slideshow</a> |  
			<a href="upload.php">Add pictures to my slideshow</a> | 
			<a href="delete.php">Delete pictures</a>
		</nav>
	</head>
	<div class="add-form">
		<form method="post" enctype="multipart/form-data">
			Select a picture to upload (max 680x250 px): 
			<input type="file" name="fileToUpload" id="fileToUpload"><br><br>
			Date:<br>
			<input type="date" name="dateFile" id="dateFile"><br><br>
			Write a description about the picture:<br>
			<textarea rows="4" cols="50" name="description" id="description"></textarea><br><br>
			<input type="submit" value="Add picture" name="add">
		</form>
	</div>
</body>
</html>

<?php

$uploadOk = 1;

$xml = simplexml_load_file("slideshow.xml");

$sxe = new SimpleXMLElement($xml->asXML());

if (isset($_POST["add"])) {
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    }
    elseif ($check == null) {
        echo "Please, upload an image.";
        $uploadOk = 0;
    }
    else {
    	echo "File is not an image.";
        $uploadOk = 0;
    }

	// Check if file already exists
	$target_dir = "uploads/";
	$targetFileName = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $targetFileName;
	if (file_exists($target_file)) {
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}

	// Allow certain file formats (JPG, JPEG, PNG, and GIF files)
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}

	/* 
	Check if $uploadOk is set to 0 by an error
	Check if date is null
	Check if the description is null
	*/
	$dateFile = $_POST["dateFile"];
    $description = $_POST["description"];
	if ($uploadOk == 0 || $dateFile == null || $description == null) {
	    echo "
	    	Sorry, your file was not uploaded.<br>
	    	Please, set a date or write a short description about the picture.
	    ";
	}
	// if everything is ok, try to upload file
	else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

	        // add <image></image>
	        $new_item = $sxe->addChild("image");

	        // add <file>VALUE</file>
			$new_item->addChild("file", $targetFileName);
			
			// add <date>VALUE</date>
			$new_item->addChild("date", $_POST["dateFile"]);

			// add <description>VALUE</description>
			$new_item->addChild("description", $_POST["description"]);

			$sxe->asXML("slideshow.xml");
	    }
	    else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
}

?>
