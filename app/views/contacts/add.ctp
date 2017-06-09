<script language="javascript">

function flashvalidation11(){

 var a = document.getElementById('ContactName').value;
 var b = document.getElementById('ContactPhoneNumber').value;
 var group_id = document.getElementById('KeywordId').value;
 var bday = document.getElementById('birthday').value;
 //alert(group_id);
 

if(group_id==''){
 
 alert('Please select  a group');
 return false;
 
 }
 /*if(a==''){
 
 alert('Please enter a name');
 return false;
 
 }*/
 if(b==''){
 
 alert('Please enter a phone number');
 return false;
 
 }else{
    var phone =(/^[+0-9]+$/);

    if(!b.match(phone)){
      alert("Please enter correct phone number with NO spaces, dashes, or parentheses.");
      return false;  
    }
 }

 if (bday !=''){
    if (!isValidDate(bday)) {
        alert('Please enter date in format: YYYY-MM-DD');
        return false;
    }
 }
 
}

function isValidDate(dateString) {

  var regEx = /^\d{4}-\d{2}-\d{2}$/;
  //alert (dateString.match(regEx));
  
  if(!dateString.match(regEx)){
    return false;  // Invalid format
  }else{

     var d;

     d = new Date(dateString);

     if(!((d = new Date(dateString))|0)){
        return false;
     }else{
        return true;
     }
     
  }

  
}

</script>

<div>
<div class="clearfix"></div>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Add Contact
	</div>
</div>



<div class="portlet-body">

<!-- login box-->
<div class="loginbox">
	<div class="loginner">
		<div class="login-left">


<div class="contacts form">
<?php echo $this->Form->create('Contact', array('id' => 'addContactForm','onsubmit'=>'return flashvalidation11();'));?>
	<input type="hidden" value="<?php echo $source; ?>" name="data[Contact][source]">
	<div class="feildbox form-group" >
			<label for="some21">Groups</label>	

		
		
			<select id="KeywordId" class="form-control txt" multiple="multiple" name="data[Group][id][]" >
			<?php
			
			foreach($groupname as $Groups){ 
		
			//$groupid=$messages['Group']['id'];
			if($Groups['Group']['keyword']!='?')
			{
			
			?>
    <option value="<?php echo $Groups['Group']['id']; ?>"><?php echo ucwords($Groups['Group']['group_name']).'('.$Groups['Group']['totalsubscriber'].')'; ?></option>
    <?php }} ?>
			
</select>
			
	
	</div>
		
		
	<div class="form-group" >	
			<label>Name</label>	
	<?php echo $this->Form->input('name',array('class' =>'form-control','div'=>false,'label'=>false));?>
	</div>
	<div class="form-group" >	
			<label>Phone Number</label>	
	<?php echo $this->Form->input('phone_number',array('class' =>'form-control','div'=>false,'label'=>false)); ?>
	</div>
        <div class="form-group" >	
			<label>Email</label>	
        <input type="email" class="form-control" id="email" name="data[Contact][email]"  />
        </div>
        <div class="form-group" >	
			<label>Birthday</label>	
        <input type="date" class="form-control" id="birthday" name="data[Contact][birthday]" placeholder="Format: YYYY-MM-DD" />
        </div>
	<label>NO spaces, dashes, or parentheses in phone numbers</label>
        <br/><label><font style="color:red">Include country code in the number</font></label>
        <br/><label>US Example: 12025248725</label>
        <label>UK Example: 447481340516</label>
<input type="Submit" value="Send" class="btn btn-primary">
<?php 
echo $this->Form->end();

?>
</div>
	</div>
	</div>
</div>
</div>
	</div>
	</div>
</div>
