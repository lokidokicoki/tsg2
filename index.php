<?php
function getConn(){
	$dbname="tsg2db";
	$dbhost="localhost";
	$dbuser="tsg2user";
	$dbpass="kantara12";
	$con=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if (mysqli_connect_errno()){
		printf('Connection failed');
		exit();
	}	
	return $con;
}

function closeConn($con){
	if($con){
		mysqli_close($con);
	}
}

function openTemplate (){
	$html = '<html>
<head>
	<title>LDC-TSG2</title>
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

function getThings($con){
	if (! $con){
		$con = getConn();
	}
	// A QUICK QUERY ON A FAKE USER TABLE
	$query = "SELECT * FROM `thing`";
	$result = $con->query($query) or die($con->error.__LINE__);

	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$html.= stripslashes($row['thingID']);	
		}
	}
	else {
		$html .= '<p>NO RESULTS</p>';	
	}

	return $html;
}

$html = openTemplate();

$con = getConn();
$html .= getThings($con);
closeConn($con);
$html .= closeTemplate();
echo $html;
?>
