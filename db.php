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
?>
