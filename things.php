<?php
function getThings($con){
	if (! $con){
		$con = getConn();
	}
	// A QUICK QUERY ON A FAKE USER TABLE
	$query = "SELECT * FROM `thing`";
	$result = $con->query($query) or die($con->error.__LINE__);
	$html='';

	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$html.= stripslashes($row['thingID']);	
		}
	}
	else {
		$html .= '<p>NO RESULTS</p><p><form action="/" id="create"><input type="submit" value="Create"/></form></p>';	
	}

	return $html;
}
?>
