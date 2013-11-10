<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
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
<canvas id="petri" width="600" height="300"></canvas>
</div>';
	return $html;
}

function closeTemplate(){
	$html = '</body>
</html>';
	return $html;
}

$user='';
$fragment="existing user";
if (isset($_COOKIE["LDCTSG2UID"])){
	$user=$_COOKIE["LDCTSG2UID"];
} else{
	$user = uniqid('UID_');
   	$expire=time()+60*60*24*30;
	setcookie("LDCTSG2UID", $user, $expire);
	$fragment="new user";
}
$html = openTemplate();

$con = getConn();
$html .= testThings($con,$user);
closeConn($con);
$html .= $fragment;
$html .= closeTemplate();
echo $html;
?>
