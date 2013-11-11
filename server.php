<?php
include ("db.php");
include("things.php");

if(isset($_POST["action"]) && !empty($_POST["action"])){
	$con = getConn();
	$user=$_COOKIE["LDCTSG2UID"];
	$action = $_POST["action"];
	$w=600;
	$h=300;
	switch($action){
	case "create":
		thingsTable($con);
		$things = createThings($con, $user, $w, $h);
		$retval = array('things'=>$things, 'control'=>'<p id="results">Have Things</p><form id="run"><input type="submit" value="Run"/></form>');
		echo json_encode($retval);
		break;
	case "run":
		$things = incubateThings($con, $user, $w, $h);
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
