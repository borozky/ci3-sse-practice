<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Server Sent Events (SSE) using CodeIgniter</title>

	<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>

<div id="container">
	<h1>Welcome to Server Sent Events (SSE) using CodeIgniter</h1>

	<div id="body">
		<button id="close">Close the connection</button>
		<button id="open">Refresh the connection</button>
		<ul>
		</ul>
		
		<script>
			var closeButton = document.getElementById('close');
			var openButton = document.getElementById('open');
			
			var evtSource = new EventSource('/welcome/sse');

			var eventList = document.querySelector('ul');

			evtSource.onopen = function() {
				openButton.disabled = true;
				closeButton.disabled = false;
				console.log("Connection to server opened.");
			};

			evtSource.onmessage = function(e) {
			var newElement = document.createElement("li");

			newElement.textContent = "message: " + e.data;
				eventList.appendChild(newElement);
			};

			evtSource.onerror = function() {
				console.log("EventSource failed.");
			};

			closeButton.onclick = function() {
				evtSource.close();
				openButton.disabled = false;
				closeButton.disabled = true;
				console.log('Connection closed');				
			};
			
			openButton.onclick = function() {
				location.reload();
				openButton.disabled = true;
			};

			evtSource.addEventListener("news", function(e) {
				var newElement = document.createElement("li");
				var pre = document.createElement('pre');
				var obj = JSON.parse(e.data);
				pre.innerHTML = JSON.stringify(obj)
				newElement.appendChild(pre)
				eventList.appendChild(newElement);
			}, false);
		</script>
	</div>
</div>

</body>
</html>