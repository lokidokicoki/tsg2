$(document).ready(function(){
//handle thing creation
$('#create').submit(function(event){
	
    // prevent default posting of form
    event.preventDefault();
	// fire off the request to /server.php
    $.post("server.php",{action:"create"})
	.done(function (data){
		alert('done:'+data);
	})
	.fail(function () {
		alert('fail');
	})
	.always(function (){
		alert('always');
	});

});
});
