var $ = require('$');
console.log(1)
$('#feedbackForm button').click(function(){
	var data = $(this).parents('form').serializeArray();
	console.log(data[1].value);
	if(data[1].value){

		console.log(1)
		var request = $.ajax({
			url: '/feedback/AxCreate',
			type: 'POST',
			data: data,
			dataType: 'json'
		});
		request.done(function(msg){
			console.log(msg);
		});
	}
	return false;
});

$('#tabs a').click(function(){
	var status = $(this).data();
	var request = $.ajax({
		url: '/feedback/AxGetMessages',
		type: 'POST',
		data: status,
		dataType: 'json'
	});
	request.done(function(msg){
		console.log(msg);
	});
});