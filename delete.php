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
	<div  class="listImageToDelete">
		<?php

		$xml = simplexml_load_file("slideshow.xml") or die("Error: Cannot create object");

		function processXML($node){

		    foreach($node->children() as $image => $data){  

		    	// Removes whitespace and other predefined characters from both sides of a string
		        $image= trim($image);   

		        // Prints the pictures and a button to delete them
		        if($image != "" && $image == 'file'){
		            echo 
		            	"<div  class='divImage'>
			            	<img class='deleteImage' src='uploads/".$data."'>
			            	<form method='post'>
				            	<input type='hidden' value='".$data."'>
				            	<input type='submit' name='delete' value='Delete image'>
			            	</form><br>
		            	</div>
		            	"
		            ;
		        }
		    	processXML($data);
		    }
		}

		processXML($xml);

		?>
	</div>
</body>
</html>

