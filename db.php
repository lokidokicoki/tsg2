<?php
function getConn(){
	$dbname="lokidoki_tsg2";
	$dbhost="localhost";
	$dbuser="lokidoki_tsg2";
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
