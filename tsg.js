$(document).ready(function(){
//handle thing creation
$('#create').submit(function(event){
	
    // prevent default posting of form
    event.preventDefault();
	// fire off the request to /server.php
    $.post("server.php",{action:"create"}, 'json')
	.done(function (data){
		console.log('done pre data:'+data);
		data = $.parseJSON(data);
		console.log('done post data:'+data);
		draw(data);
	})
	.fail(function () {
		console.error('fail');
	});
	/*
	.always(function (){
		console.log('always');
	});
	*/

});

function draw(data){
	if (data && data !== undefined){
		if (data.things){
			for(var i=0,len=data.things.length;i<len;i++){
				console.debug(data.things[i]);
			}
		}
	}
};
});
