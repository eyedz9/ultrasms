function validateForm(form, rules) {//alert('HERE');
  //clear out any old errors 
  $("#validationMessages").html(""); 
  //$("#validationMessages").hide(); 
  $(".error-message").hide(); 
  var errorMsg = ""; 
   
  //loop through the validation rules and check for errors 
  $.each(rules, function(field) { 
    var val = $.trim($("#" + field).val()); 
     
    $.each(this, function() { 
      //console.log(this['rule']); 
       
      //check if the input exists 
      if ($("#" + field).attr("id") != undefined) { 
        var valid = true;
        if (this['allowEmpty'] && val == '') { 
          //do nothing 
		  return;
        } else if (this['rule'].match(/^range/)) { 
          var range = this['rule'].split('|'); 
          if (val < parseInt(range[1])) { 
            valid = false; 
          } 
          if (val > parseInt(range[2])) { 
            valid = false; 
          } 
        } else if (this['negate']) { 
          if (val.match(eval(this['rule']))) { 
            valid = false; 
          } 
        } else if (!val.match(eval(this['rule']))) { 
          valid = false; 
        } 
         
        if (!valid) {
          //add the error message 
		  errorMsg += "<p>" + this['message'] + "</p>";
          //$("#validationMessages").append("<p>" + this['message'] + "</p>"); 
		  
		  //highlight the label 
          //$("label[for='" + field + "']").addClass("error"); 
          $("#" + field).parent().addClass("error"); 
        } 
      } 
    }); 
  });
  if(errorMsg != ""){
	$("#validationMessages").html(errorMsg);
	$("#validationMessages").show();
	$("#validationMessages").focus();
	return false;
  }
  else{
	$("#validationMessages").hide();  
	return true;
	}
}  