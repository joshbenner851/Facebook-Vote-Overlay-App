<!DOCTYPE html>
<html>
<head>
	<title>temp</title>
	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="materialize/css/materialize.min.css"  media="screen,projection"/>

	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
	<?php //include_once 'login.php'; ?>
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
      checkAlbum();
      uploadAlbum(url);
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
  		appId      : '970957542980333',
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

  function checkAlbum(){
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
  			uploadAlbum();
  		}
  		else{
  			uploadPic(albumID);
  		}
  	});
  }

  function uploadAlbum(url){
  	FB.api(
  		"/me/albums", 'post', {name: "political", privacy: {value: "SELF"}}, 
  		function(response){
  			console.log("Updated album");
  			console.log(response);
  			uploadPic(response.id,url);
  		});
  }

  function uploadPic(albumID,url){
  	FB.api('/'+albumID+'/photos', 'post', {url: url},
  		function (response){
  			console.log(response);
  			redirectProfilePic(albumID, response.id);
  		});
  }


  function redirectProfilePic(albumID, photoID){
  	window.location.replace("http://www.facebook.com/photo.php?fbid="+photoID+"&makeprofile=1");
  }

  function testPic(){
  	FB.api(
  		"/me/picture?width=500&height=500",
  		function (response) {
			if (response && !response.error) {
				var data = response.data;
				console.log(response.data);
				$('#profPic').attr('src', data.url);
				var canvas = document.getElementById("canvas");
				var ctx = canvas.getContext("2d");
				var img = document.getElementById("profPic");
				//$('#filter').css("opacity",".4");
				var filter = document.getElementById("filter");
				filter.style.opacity = ".5";
				ctx.drawImage(img, 10, 10);
				ctx.drawImage(filter,40,20);
				ctx.font = "40px Arial";
				ctx.fillStyle = "#fff";
				ctx.fillText("I voted! If you didn't vote I will personally", 30, 200);
				ctx.fillText("blame you if Trump becomes president", 30, 300);
				$('#profPic').css("display","none");
				$('#filter').css("margin-left","-999em");
				$('#profPic').attr('src', canvas.toDataURL());
				console.log(canvas.toDataURL().toString());
				return canvas.toDataURL().toString();
			}
		});
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

<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>


<div id="status">
</div>

<div class="center">
	<img id="profPic" src="" >
	<canvas id="canvas" width="600" height="600"></canvas>
</div>

<div class="center">
	<h2>Logout/in Area</h2>
	<input type="button" onclick="login();" value="login">
	<input type="button" onclick="logout();" value="logout">
</div>
<img id="filter" src="12047659_10209208455187216_73273559_n.jpg">

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="materialize/js/materialize.min.js"></script>
</body>
</html>