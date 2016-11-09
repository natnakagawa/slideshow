/*
The XMLHttpRequest object is used to exchange data with a server behind the scenes.
*/
var ajax = new XMLHttpRequest();
var currentImage = 0;

ajax.onreadystatechange = function() {
	if(ajax.readyState == 4 && ajax.status == 200) {
		console.log("OK, fil inladdad");
		parseXML();
	}
}

/* Specifies the type of request */
ajax.open("GET", "slideshow.xml", true);

/* Send a request to a server */
ajax.send();

/* Reads the XML file and gets its content */
function parseXML() {
	var xml = ajax.responseXML; // Gets the response data as XML data

	var images = xml.getElementsByTagName("image");

	var slideshow = "<ul id='slideshow'>";

	for (var i = 0; i < images.length; i++) {
		if (images[i].children) {
			var imageFile = "uploads/" + images[i].children[0].innerHTML; // Gets the pictures
			slideshow += "<li class='imageList'><img id='imageFile' src='"+imageFile+"'><br>";
			slideshow += "<span id='date'>" + images[i].children[1].innerHTML + "</span><br>"; // Gets the date
			slideshow += "<span id='description'>" + images[i].children[2].innerHTML + "</span>"; // Gets the description
		}
	}
	slideshow += "</li></ul>";
	slideshow += "<a class='navigation' id='prev'></a>";
	slideshow += "<a class='navigation' id='next'></a>";

	document.getElementById("picture").innerHTML = slideshow;
	document.getElementsByClassName("imageList")[currentImage].style.zIndex = '2'; // Displays the first picture
	document.getElementById("prev").addEventListener("click", prevImage);
	document.getElementById("next").addEventListener("click", imageNext);
	document.addEventListener("keydown", handleKeyDown);
}

/* Displays the next picture when the "next" button is clicked */
function imageNext() {
	var slides = document.getElementsByClassName("imageList");

	for (var i = 0; i < slides.length; i++) {
		slides[i].style.zIndex = '1';
	}
	
	if (currentImage < slides.length-1) {
		// ++currentImage increments the variable, returning the new value
		// Displays the next picture in front of the others
		slides[++currentImage].style.zIndex = '2';
	}
	else {
		slides[0].style.zIndex = '2';
		currentImage = 0;
	}
}

/* Displays the previous picture when the "prev" button is clicked */
function prevImage() {
	var slides = document.getElementsByClassName("imageList");

	for (var i = 0; i < slides.length; i++) {
		slides[i].style.zIndex = '1';
	}
	
	if (currentImage > 0) {
		// --currentImage decrements the variable, returning the new value
		// Displays the previous picture in front of the others
		slides[--currentImage].style.zIndex = '2';
	}
	else {
		currentImage = slides.length-1;
		slides[currentImage].style.zIndex = '2';
	}
}

/* Displays the next or previous picture by pressing arrow keys */
function handleKeyDown(e) {
	var slides = document.getElementsByClassName("imageList");

	for (var i = 0; i < slides.length; i++) {
		slides[i].style.zIndex = '1';
	}

	// Displays the next picture
	if (e.keyCode === 39) {
		slides[++currentImage].style.zIndex = '2';
		console.log("going right");
	}

	// Displays the previous picture
	if (e.keyCode === 37) {
		slides[--currentImage].style.zIndex = '2';
		console.log("going left");
	}
	
}
