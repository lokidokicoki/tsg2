<?php
include ("db.php");
include("things.php");

if(isset($_POST["action"]) && !empty($_POST["action"])){
	$con = getConn();
	$user=$_COOKIE["LDCTSG2UID"];
	$action = $_POST["action"];
	switch($action){
	case "create":
		$things = createThings($con, $user);
		$retval = array('things'=>$things);
		echo json_encode($retval);
		break;
	default:
		echo "unknown action".$action;
		break;
	};
	closeConn($con);
}
?>
