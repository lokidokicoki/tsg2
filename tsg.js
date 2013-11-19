var running = false;
var selectedID = 0;
var tick = 250;

function updateInfo(thing) {
	'use strict';
	if (!thing || thing === undefined){
		return;
	}

	$('#thingID').text(thing.thingID);
	$('#energy').text(thing.energy);
	$('#pos').text(thing.posx +','+ thing.posy);
}

function run(event){
	'use strict';
	console.log('run');
	// prevent default posting of form
	if (event && event !== undefined){
		event.preventDefault();
	}
	// fire off the request to /server.php
	$.post('server.php',{action:'run'}, 'json')
	.done(function (data){
		//console.log('done pre data:'+data);
		data = $.parseJSON(data);
		//console.log('done post data:'+data);
		draw(data);

		if(running){
			$('#runPause').attr('value', 'Pause');
			setTimeout(run, tick);
		}else{
			$('#runPause').attr('value', 'Run');
		}
		
	})
	.fail(function () {
		console.error('run fail');
	});
}

function drawStuff (ctx, data) {
	'use strict';
	// stuff is green!
	for(var x=0,xlen=data.stuff.length;x<xlen;x++){
		for(var y=0, ylen=data.stuff[x].length; y<ylen; y++){
			if (data.stuff[x][y] === 1){
				ctx.fillStyle = 'rgb(0,128,0)';
				ctx.fillRect(x, y, 1, 1);
			}
			/*else if (data.stuff[x][y] === 2){
				ctx.fillStyle = 'rgb(0,0,255)';
				ctx.fillRect(x, y, 1, 1);
			}*/
		}
	}
}

function drawThings (ctx, data) {
	'use strict';
	for(var i=0,len=data.things.length;i<len;i++){
		//console.debug(data.things[i]);
		var thing = data.things[i];
		var select = 0;
		var kid = 0;
		var energy = thing.energy;

		// get selected status....
		if (selectedID && thing.thingID === selectedID){
			select = 255;
			updateInfo(thing);
		}

		if (thing.kid){
			kid = 255;
		}

		// colour dependant on energy value
		if (energy > 200) {
			ctx.fillStyle = 'rgb(255,'+kid+','+select+')';
		}else if (energy > 100) {
			ctx.fillStyle = 'rgb(128,'+kid+','+select+')';
		}else{
			ctx.fillStyle = 'rgb(64,'+kid+','+select+')';
		}

		// render things as a square
		// thing x,y is position of 'mouth'
		ctx.fillRect(thing.posx - 1,thing.posy - 1, 3, 3);
	}
}


/**
 * Wrapper function around stuff & thing draw functions.
 * @param data jqXHR result
 */
function draw (data){
	'use strict';
	var canvas = $('#petri')[0];
	var ctx = canvas.getContext('2d');
	//reset canvas
	canvas.width=canvas.width;

	if (!data || data === undefined){
		return;
	}

	if (data.stuff){
		drawStuff(ctx, data);
	}

	if (data.things){
		drawThings(ctx, data);
	}
}

function click(e){
	'use strict';
	var x = e.offsetX - 2;
	var y = e.offsetY - 2;
	$('#clicked').text(x+','+y);
	running = false;
	// fire off the request to /server.php
	$.post('server.php',{action:'info', x:x, y:y}, 'json')
	.done(function (data){
//		console.log('done pre data:'+data);
		data = $.parseJSON(data);
//		console.log('done post data:'+data);
		//draw(data);
		selectedID = null;
		if (data && data !== undefined && data.length > 0){
			selectedID = data[0].thingID;
			updateInfo(data[0]);
		}

	})
	.fail(function () {
		console.error('run fail');
	});
}

$(document).ready(function(){
	'use strict';
	$('#petri').on('click', click);
	//handle thing creation
	$('#create').submit(function(event){
	
		// prevent default posting of form
		event.preventDefault();
		// fire off the request to /server.php
		$.post('server.php',{action:'create'}, 'json')
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
