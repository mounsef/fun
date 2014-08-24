<html>
<head>
<script>
function Calculate(str)
{
	if (str=="")
	  {
		  document.getElementById("result").innerHTML="<b>Værdi må ikke være tom</b>";
		  return;
	  } 
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	
	xmlhttp.onreadystatechange=function()
	  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("result").innerHTML=xmlhttp.responseText;
				bills();
			}
	  }
	  
	xmlhttp.open("GET",'http://localhost/fun/sweet.php?q='+str,true);
	xmlhttp.send();
}



function bills()
{
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	xmlhttp.onreadystatechange=function()
	  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("bills").innerHTML=xmlhttp.responseText;
			}
	  }
	  
	xmlhttp.open("GET","http://localhost/fun/bills.php",true);
	xmlhttp.send();
}

</script>
</head>
<body onLoad="bills()">
<?php
$con=mysqli_connect("127.0.0.1","root","", "monsef");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>
<div id="main">
<div "top">
<h2>Préciser le montant</h2>
<input id="q" type="text">
<input type="submit" onClick="Calculate(q.value);" name="submit" value="Godkend">

<br><br>
</div>
<div id="result"><b>Resultat</b></div>
<div id="bills"></div>
</div>
</body>

</html>
