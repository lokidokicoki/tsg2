<?php

function thingsTable($con){
	$query="create table if not exists thing (
	thingID integer not null auto_increment,
	userID char(255) not null,
	posx integer not null,
	posy integer not null,
	age integer,
	direction integer,
	energy float(10,2),
	genes text,
	ancestors text,
	primary key (thingID, userID),
	index thing_idx (posx,posy)
);";
	$result = $con->query($query) or die($con->error.__LINE__);
}

function testThings($con,$user){
	if($con->query("SHOW TABLES LIKE 'thing'")->num_rows == 0){
		thingsTable($con);
	}

	// A QUICK QUERY ON A FAKE USER TABLE
	$query = "SELECT * FROM `thing` where userID='$user'";
	$result = $con->query($query) or die($con->error.__LINE__);
	$html='';

	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		$html .= '<div id="control"><p id="results">';
		while($row = $result->fetch_assoc()) {
			$html.= stripslashes($row['thingID']);	
		}
		$html .= '</p><form id="run"><input type="submit" value="Run"></form></p></div>';
	}
	else {
		$html .= '<div id="control"><p id="results">NO RESULTS</p><p><form id="create"><input type="submit" value="Create"/></form></p></div>';	
	}

	return $html;
}

function createThings($con, $user, $w, $h){
	$query = "insert into thing (userID, posx, posy, age, direction, energy, genes, ancestors) values ";

	for ($i=0; $i<19; $i++){
		if ($i>0){
			$query .= ",";
		}
		$posx = rand(0,$w);
		$posy = rand(0,$h);
		$age=0;
		$energy = 300;
		$direction=0;
		$ancestors='';
		$genes=json_encode(array('speed'=>1, 'tumble'=>10, 'hunt'=>30, 'efficiency'=>1));

		$query .=" ('$user', $posx, $posy, $age, $direction, $energy, '$genes', '$ancestors')";

	}

	$con->query($query) or die($con->error.__LINE__);

	return getThings($con, $user);
}

function incubateThings($con, $user, $w, $h){
	$things = getThings($con, $user);

	$sql = "update `thing` set `posx`=%s, `posy`=%s where `thingID`=%s";
	// modify things in place, hence &$thing;
	foreach($things as $key => $thing){
		$x = $thing['posx'] + rand(0,5)*(rand(0,1)*2-1);
		if ($x < 0)
			$x=0;
		elseif ($x > $w)
			$x=$w;
		$things[$key]['posx'] = $x;

		$y = $thing['posy'] + rand(0,5)*(rand(0,1)*2-1);
		if ($y < 0)
			$y=0;
		elseif ($y > $h)
			$y=$h;
		$things[$key]['posy'] = $y;
		$query = sprintf($sql,$things[$key]['posx'], $things[$key]['posy'], $things[$key]['thingID']);
		$con->query($query) or die($con->error.__LINE__);
	}

	return $things;
}

function getThings($con, $user){
	$query = "SELECT * FROM `thing` where userID='$user'";
	$result = $con->query($query) or die($con->error.__LINE__);
	$things = array();
	// loop over return results, decode genes, push to $things fro return
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$row['genes'] = json_decode($row['genes']);
			$row['posx'] = (int)$row['posx'];
			$row['posy'] = (int)$row['posy'];
			$row['age'] = (int)$row['age'];
			$row['direction'] = (int)$row['direction'];
			$row['energy'] = (float)$row['energy'];

			array_push($things, $row);
		}
	}
	return $things;
}
?>
