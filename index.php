<?php
include ("db.php");
include ("things.php");

function openTemplate (){
	$html = '<html>
<head>
	<title>LDC-TSG2</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="tsg.js"></script>
	<link rel="stylesheet" href="css/style.css"/>
</head>
<body>
<h2>Things, Stuff and Gack 2.0</h2>
<div>
<canvas id="petri"></canvas>
</div>';
	return $html;
}

function closeTemplate(){
	$html = '</body>
</html>';
	return $html;
}

$html = openTemplate();

$con = getConn();
$html .= getThings($con);
closeConn($con);
$html .= closeTemplate();
echo $html;
?>
