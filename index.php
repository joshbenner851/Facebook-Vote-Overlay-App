<!DOCTYPE html>
<html>
<head>
	<title>Poltical Shamer</title>
	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="materialize/css/materialize.min.css"  media="screen,projection"/>
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
	<div class="center">
		<h2>Step 1: Login to Facebook</h2>
		<h2>Logout/in Area</h2>
		<input type="button" onclick="login();" value="login">
		<input type="button" onclick="logout();" value="logout">
	</div>
	<div>
		<div class="center">
			<h2>Step 2: Select your filter</h2>
		</div>
		<div class="center">
			<img id="profPic" src="" >
			<p id="canvasHolder"></p>
			<p id="pngHolder"></p>
		</div>
		<div class="filters" id="filters" style="margin-left: -999em; position:absolute;">
			<img id="clinton" src="Filters/VoteShamer-Filter-Clinton.png">
			<img id="cruz" src="Filters/VoteShamer-Filter-Cruz.png">
			<img id="kasich" src="Filters/VoteShamer-Filter-Kasich.png">
			<img id="rubio" src="Filters/VoteShamer-Filter-Rubio.png">
			<img id="sanders" src="Filters/VoteShamer-Filter-Sanders.png">
			<img id="trump" src="Filters/VoteShamer-Filter-Trump.png">
		</div>
		<div class="center">
			<input type="button" onclick="uploadAlbum(url);" value="Confirm Filter">
		</div>
	</div>
	<div class="center">
		<h2>Step 3: Update your profile picture.</h2>
		<h3>Please tag your friends and get them to vote.</h3>
		<h4 id="countdown"></h4> 
		<input type="button" onclick="updateProfile();" value="submit"> 
	</div>


	<!--Import jQuery before materialize.js-->
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="materialize/js/materialize.min.js"></script>
	<script>
			// This is called with the results from from FB.getLoginStatus().
			function statusChangeCallback(response) {
				console.log('statusChangeCallback');
				console.log(response);
				// The response object is returned with a status field that lets the
				// app know the current login status of the person.
				// Full docs on the response object can be found in the documentation
				// for FB.getLoginStatus().
				if (response.status === 'connected') {
					// Logged into your app and Facebook.
					//window.location.replace("fb-callback.php");
					testAPI();
					var url = testPic();
					//checkAlbum();
					//uploadAlbum(url);
				} else if (response.status === 'not_authorized') {
					// The person is logged into Facebook, but not your app.
					document.getElementById('status').innerHTML = 'Please log ' +
					'into this app.';
				} else {
					// The person is not logged into Facebook, so we're not sure if
					// they are logged into this app or not.
					document.getElementById('status').innerHTML = 'Please log ' +
					'into Facebook.';
				}
			}

			// This function is called when someone finishes with the Login
			// Button.  See the onlogin handler attached to it in the sample
			// code below.
			function checkLoginState() {
				FB.getLoginStatus(function(response) {
					statusChangeCallback(response);
				});
			}

			window.fbAsyncInit = function() {
				FB.init({
					appId      : '970982232977864',
					cookie     : true,  // enable cookies to allow the server to access
										// the session
					xfbml      : true,  // parse social plugins on this page
					version    : 'v2.5' // use graph api version 2.5
				});

				FB.getLoginStatus(function(response) {
					statusChangeCallback(response);
				});

			};

			// Load the SDK asynchronously
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

			// Here we run a very simple test of the Graph API after login is
			// successful.  See statusChangeCallback() for when this call is made.
			function testAPI() {
				console.log('Welcome!  Fetching your information.... ');
				FB.api('/me', function(response) {
					console.log('Successful login for: ' + response.name);
					document.getElementById('status').innerHTML =
					'Thanks for logging in, ' + response.name + '!';
				});
			}

			function uploadAlbum(url){
				FB.api('me/albums', function(response){
					data = response.data;
					console.log(response.data);
					var albumExists = false;
					var albumID = "";
					for(var i=0; i<data.length; i++){
						if(data[i].name==="political"){
							albumExists = true;
							albumID = data[i].id;
							break;
						}
					}
					if(!albumExists){
						createAlbum(url);
					}
					else{
						uploadPic(albumID, url);
					}
				});
			}

			function createAlbum(url){
				FB.api(
					"/me/albums", 'post', {name: "political", privacy: {value: "SELF"}},
					function(response){
						console.log("Updated album");
						console.log(response);
						uploadPic(response.id,url);
					});
			}

			function initiateCountdown(){
				var cc = 5;

				var interval = setInterval(function()
				{
					document.getElementById("Countdown").innerHTML = "Redirecting to Facebook in " + -- cc+".";

					if (cc == 0)
						clearInterval(interval);

				}, 1000);
			}

			function uploadPic(albumID,url){
				FB.api('/'+albumID+'/photos', 'post', {url: url},
					function (response){
						console.log(response);
						initiateCountdown();
						redirectProfilePic(albumID, response.id);
					});
			}

			function redirectProfilePic(albumID, photoID){
				window.location.replace("http://www.facebook.com/photo.php?fbid="+photoID+"&makeprofile=1");
			}

			function testPic(){
				FB.api(
					"/me/picture?width=300&height=300",
					function (response)
					{
						if (response && !response.error) {
							var data = response.data;
							console.log(response.data);
							$('#profPic').attr('src', data.url);
								//get the profile picture image
								var sampleImage = document.getElementById("#profPic"),
								canvas = convertImageToCanvas(sampleImage);
								//Add the image to the Canvas and convert the Canvas to a regular image again
								document.getElementById("canvasHolder").appendChild(canvas);
								document.getElementById("pngHolder").appendChild(convertCanvasToImage(canvas));
								var img = document.getElementById("pngHolder").getAttribute("src");
								console.log(img);
								//var img = document.getElementById("profPic");
								$('#profPic').css("display","none");
								$('.filters').css("margin-left","-999em");
								$('#filters').css("margin-left","-999em");
								//$('#profPic').attr('src', canvas.toDataURL("image/png",0));
								//console.log("trying to image" ,canvas.toDataURL().toString("image/png"));
								//return canvas.toDataURL().toString();
							}
						});
			}

			//convert image to canvas
			function convertImageToCanvas(image) {
				var canvas = document.createElement("canvas");
				var filter = document.getElementById("trump");
				filter.style.opacity = ".5";
				canvas.width = image.width;
				canvas.height = image.height;
				canvas.getContext("2d").drawImage(image, 0, 0);
				//canvas.drawImage(filter,10,10)
				return canvas;
			}

			// Convert canvas to image
			function convertCanvasToImage(canvas){
				var image = new Image();
				image.src = canvas.toDataURL("image/png");
				return image;
			}

			function login(){
				FB.login(function(response) {
					if (response.authResponse) {
						console.log('Welcome!  Fetching your information.... ');
						FB.api('/me', function(response) {
							console.log('Good to see you, ' + response.name + '.');
						});
					} else {
						console.log('User cancelled login or did not fully authorize.');
					}
				}, {scope: 'user_photos,publish_actions'});
			}

			function logout(){
				FB.logout(function (response){

				});
				FB.getLoginStatus(function(response){
					statusChangeCallback(response);
				});

			}
		</script>
	</body>
	</html>