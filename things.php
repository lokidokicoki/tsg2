<?php
function testThings($con,$user){
	// A QUICK QUERY ON A FAKE USER TABLE
	$query = "SELECT * FROM `thing` where userID='$user'";
	$result = $con->query($query) or die($con->error.__LINE__);
	$html='';

	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$html.= stripslashes($row['thingID']);	
		}
		$html .= '<form id="run"><input type="submit" value="Run"></form>';
	}
	else {
		$html .= '<p>NO RESULTS</p><p><form action="/" id="create"><input type="submit" value="Create"/></form></p>';	
	}

	return $html;
}

function createThings($con, $user){
	// only create 1 thing at present
	$posx = rand(0,300);
	$posy = rand(0,200);
	$age=0;
	$energy = 300;
	$direction=0;
	$ancestors='';
	$genes=json_encode(array('speed'=>1, 'tumble'=>10, 'hunt'=>30, 'efficiency'=>1));

	$query = "insert into thing (userID, posx, posy, age, direction, energy, genes, ancestors) ".
	   "values ('$user', '$posx', '$posy', '$age', '$direction', '$energy', '$genes', '$ancestors')";

	$con->query($query) or die($con->error.__LINE__);

	return getThings($con, $user);
}

function getThings($con, $user){
	$query = "SELECT * FROM `thing` where userID='$user'";
	$result = $con->query($query) or die($con->error.__LINE__);
	$things = array();
	// loop over return results, decode genes, push to $things fro return
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$row['genes'] = json_decode($row['genes']);

			array_push($things, $row);
		}
	}
	return $things;
}
?>
