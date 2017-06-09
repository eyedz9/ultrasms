	var path = '/';
function searchNumber(){

	
	$.ajax({type: "POST",url: path+"twilios/searchnumber",
    data: {area:$('#TwilioAreacode').val()},
    success: function(result) {
        $('.nyroModalLink').html(result);
    }});

	
}

function assignthisnumber(numbertoassign){
	
	$.ajax({type: "POST",url: path+"twilios/assignthisnumber",
    data: {number:numbertoassign},
    success: function(result) {
        window.location = 'profile';
    }});

	
}