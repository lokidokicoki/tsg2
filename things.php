<?php

/**
 * Create thing table
 */
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

	// get thing count
	$query = "SELECT * FROM `thing` where userID='$user'";
	$result = $con->query($query) or die($con->error.__LINE__);
	$html='';

	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		$html .= '<div id="control"><p id="results">';
		$html .= 'Have Things';
		$html .= '</p><form id="run"><input id="runPause" type="submit" value="Run"></form></p><div id="clicked">0,0</div>'.
			'<div id="info">
				<span>Thing Info</span>
				<span id="thingID">0</span>
			</div>'.
			'</div>';
	}
	else {
		$html .= '<div id="control">'.
			'<p id="results">NO RESULTS</p>'.
			'<p><form id="create"><input type="submit" value="Create"/></form></p>'.
			'<div id="clicked">0,0</div>'.
			'<div id="info">
				<span>Thing Info</span>
				<span id="thingID">0</span>
			</div>'.
			'</div>';	
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

	// find things that are sat on top of 'stuff'. eat them!
	$query=  "SELECT t.thingID as thingID, t.posx as x, t.posy as y FROM thing AS t JOIN stuff AS s ON t.userID = s.userID AND t.posx = s.x AND t.posy = s.y AND s.cell =1";
	$sql = "update `stuff` set `cell`=2 where `x`=%d and `y`=%d and `userID`='%s'";
	$result = $con->query($query) or die($con->error.__LINE__);
	$target = array();
	if ($result->num_rows > 0){
		//error_log('eat stuff!');
		while($row = $result->fetch_assoc()) {
			$target[$row['thingID']] = $row;

			//'kill' stuff at that location
			//error_log($sql);
			$query = sprintf($sql, $row['x'], $row['y'], $user);
			//error_log($query);
			$con->query($query) or die($con->error.__LINE__);
		}
	}

	// turn this into an 'object' keyed by the thing id

	$sql = "update `thing` set `posx`=%s, `posy`=%s where `thingID`=%s";
	// modify things in place, hence &$thing;
	foreach($things as $key => $thing){
		//error_log('incubate '.$thing['thingID']);
		if (isset($target[$thing['thingID']])){
			//error_log('got thing in targets');
			$things[$key]['energy'] += 5;
		}
		// simple random jiggling at present
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
		$things[$key]['energy'] -= 2;
		$things[$key]['posy'] = $y;
		$query = sprintf($sql, $things[$key]['posx'], $things[$key]['posy'], $things[$key]['thingID']);
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

function getThingAtCoords($con, $user, $x, $y){
	$xmin = $x-5;
	$xmax = $x+5;
	$ymin = $y-5;
	$ymax = $y+5;
	$query = "SELECT * FROM `thing` where userID='$user' and posx>=$xmin and posx<=$xmax and posy>=$ymin and posy <= $ymax";
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
