<?php
include ("db.php");
include("things.php");
include ("stuff.php");

if(isset($_POST["action"]) && !empty($_POST["action"])){
	$con = getConn();
	$user=$_COOKIE["LDCTSG2UID"];
	$action = $_POST["action"];
	$w=600;
	$h=300;
//	error_log($action);
	switch($action){
	case "create":
		thingsTable($con);
		stuffTable($con);
		$things = createThings($con, $user, $w, $h);
		$stuff = createStuff($con, $user, $w, $h, true);
		$retval = array('stuff'=>$stuff, 'things'=>$things, 'control'=>'<p id="results">Have Things</p><form id="run"><input type="submit" value="Run"/></form>');
		echo json_encode($retval);
		break;
	case "run":
		$stuff = incubateStuff($con, $user, $w, $h);
		$things = incubateThings($con, $user, $w, $h);
		$retval = array('things'=>$things, 'stuff'=>$stuff);
		echo json_encode($retval);
		break;
	case "info":
		$thing = getThingAtCoords($con, $user, $_POST["x"], $_POST["y"]);
		echo json_encode($thing);
		break;
	case "init":
		$stuff = getStuff($con, $user, $w, $h);
		echo json_encode($stuff);
		break;
	default:
		echo "unknown action".$action;
		break;
	};
	closeConn($con);
}
?>
