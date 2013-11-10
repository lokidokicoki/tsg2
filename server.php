<?php
if(isset($_POST["action"]) && !empty($_POST["action"])){
	$action = $_POST["action"];
	switch($action){
	case "create":
		echo "create";
		break;
	default:
		echo "unknown action".$action;
		break;
	};
}
?>
