<?php
function stuffTable($con){
	$query="create table if not exists stuff (
	x integer not null,
	y integer not null,
	userID char(255) not null,
	cell integer default 0,
	primary key (x, y, userID)
);";
	$result = $con->query($query) or die($con->error.__LINE__);
}

function testStuff($con,$user, $w, $h){
	if($con->query("SHOW TABLES LIKE 'stuff'")->num_rows == 0){
		stuffTable($con);
	}

	$query = "SELECT * FROM `stuff` where userID='$user' order by x,y";
	$result = $con->query($query) or die($con->error.__LINE__);
	if($result->num_rows == 0){
		$stuff = createStuff($con, $user, $w, $h, true);
		incubateStuff($con, $user, $w, $h);
	}
}

function createStuff($con, $user, $w, $h, $populate=false){
	$a = array(); // array of columns
	$query = "insert into stuff (x,y,userID,cell) values ";
	for($c=0; $c<$w; $c++){
    	$a[$c] = array(); // array of cells for column $c
    	for($r=0; $r<$h; $r++){
			if ($populate){
				$v = rand(0,1);
				$a[$c][$r] = $v;
				$query .= " ('$c','$r','$user','$v'),";
			}else{
				$a[$c][$r] = 0;
			}
    	}
	}

	if ($populate){
		$query = rtrim($query, ',');
		$con->query($query) or die($con->error.__LINE__);
	}
	return $a;
}

function incubateStuff($con, $user, $w, $h){
	$stuff = getStuff($con, $user, $w, $h);
	return $stuff;
}

function getStuff($con, $user, $w, $h){
	$query = "SELECT * FROM `stuff` where userID='$user' order by y,x";
	$result = $con->query($query) or die($con->error.__LINE__);
	$stuff = createStuff($con, $user, $w, $h);
	// loop over return results, decode genes, push to $things fro return
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$x = (int)$row['x'];
			$y = (int)$row['y'];
			$cell = (int)$row['cell'];
			$stuff[$x][$y] = $cell;
		}
	}
	return $stuff;
}

?>
