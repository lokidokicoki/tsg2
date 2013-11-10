$(document).ready(function(){
var running = false;
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
			setTimeout(run, 500);
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
		if (data.things){
			for(var i=0,len=data.things.length;i<len;i++){
				//console.debug(data.things[i]);
				var thing = data.things[i];
				var select = 0;
				var kid = 0;
				if (thing.selected){
					select = 255;
				}
				if (thing.kid){
					kid = 255;
				}

				ctx.beginPath();
				var fill='';
				var energy = thing.energy;
				if (energy > 200) {
					ctx.fillStyle = "rgb(255,"+kid+","+select+")";
				}else if (energy > 100) { 
					ctx.fillStyle = "rgb(128,"+kid+","+select+")";
				}else{
					ctx.fillStyle = "rgb(64,"+kid+","+select+")";
				}
				ctx.arc(thing.posx*scale,thing.posy*scale, 1.5*scale, 0, rad360);
				ctx.fill();

			}
		}
	}
};
});
