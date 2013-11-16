var running = false;
function run(event){
	console.log('run');
    // prevent default posting of form
	if (event && event != undefined){
    	event.preventDefault();
	}
	// fire off the request to /server.php
    $.post("server.php",{action:"run"}, 'json')
	.done(function (data){
		//console.log('done pre data:'+data);
		data = $.parseJSON(data);
		//console.log('done post data:'+data);
		draw(data);

		if(running){
			$('#runPause').attr('value', 'Pause');
			setTimeout(run, 500);
		}else{
			$('#runPause').attr('value', 'Run');
		}
		
	})
	.fail(function () {
		console.error('run fail');
	});
}

function draw(data){
	var canvas = document.getElementById('petri');
	var ctx = canvas.getContext('2d');
	//reset canvas
	canvas.width=canvas.width;
	var scale = 1;
	var rad360 = Math.PI * 2;

	if (data && data !== undefined){
		ctx.fillStyle = "rgb(0,128,0)";
		if (data.stuff){
			for(var x=0,xlen=data.stuff.length;x<xlen;x++){
				for(var y=0, ylen=data.stuff[x].length; y<ylen; y++){
					if (data.stuff[x][y] === 1){ 
						ctx.fillRect(x, y, 1, 1);
					}
				}
			}
		}

		if (data.things){
			for(var i=0,len=data.things.length;i<len;i++){
				//console.debug(data.things[i]);
				var thing = data.things[i];
				var select = 0;
				var kid = 0;
				var fill='';
				var energy = thing.energy;

				// get selected status....
				if (thing.selected){
					select = 255;
				}
				if (thing.kid){
					kid = 255;
				}

				// colour dependant on energy value
				if (energy > 200) {
					ctx.fillStyle = "rgb(255,"+kid+","+select+")";
				}else if (energy > 100) { 
					ctx.fillStyle = "rgb(128,"+kid+","+select+")";
				}else{
					ctx.fillStyle = "rgb(64,"+kid+","+select+")";
				}

				// render things as a square
				// thing x,y is position of 'mouth'
				ctx.fillRect(thing.posx - 1,thing.posy - 1, 3, 3);
			}
		}
	}
};

function click(e){
	var x = e.offsetX - 2;
	var y = e.offsetY - 2;
	$('#clicked').text(x+','+y);
	running = false;
	// fire off the request to /server.php
    $.post("server.php",{action:"info", x:x, y:y}, 'json')
	.done(function (data){
//		console.log('done pre data:'+data);
		data = $.parseJSON(data);
//		console.log('done post data:'+data);
		//draw(data);
		if (data && data !== undefined && data.length > 0){
			$('#thingID').text(data[0].thingID);
		}

	})
	.fail(function () {
		console.error('run fail');
	});
};
$(document).ready(function(){
$('#petri').on('click', click);
//handle thing creation
$('#create').submit(function(event){
	
    // prevent default posting of form
    event.preventDefault();
	// fire off the request to /server.php
    $.post("server.php",{action:"create"}, 'json')
	.done(function (data){
		//console.log('done pre data:'+data);
		data = $.parseJSON(data);
		//console.log('done post data:'+data);
		draw(data);

		$('#control').empty().html(data.control);

		//can't work out how to tell run to trigger...
	})
	.fail(function () {
		console.error('fail');
	});
});

//handle thing creation
$('#run').submit(function(event){
	running =!running;
	run(event);
});

});
