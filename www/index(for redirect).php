<?php

?>

<!DOCTYPE html>
<html>
<head>
<script>
var currentLocation = window.location.href;

var strURL = currentLocation;
//strURL = strURL.substring(0, strURL.length - 1); // 
strURL = strURL.slice(0, -1); // 

strURL = strURL + ":1880/ui";

window.location.href = strURL;



function myFunction() {
  document.getElementById("demo").innerHTML = strURL;
}

</script>
</head>
<body>

<h2>JavaScript in Head</h2>

<p id="demo">A Paragraph.</p>

<button type="button" onclick="myFunction()">Try it</button>

</body>
</html> 


