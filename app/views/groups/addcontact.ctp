
<script>

function flashvalidation11(){

 var a = document.getElementById('ContactName').value;
 var b = document.getElementById('ContactPhoneNumber').value;
 var group_id = document.getElementById('KeywordId').value;
 //alert(group_id);
 

if(group_id==''){
 
 alert('Please select  a group');
 return false;
 
 }
 if(a==''){
 
 alert('Please enter a name');
 return false;
 
 }
 if(b==''){
 
 alert('Please enter a phone number');
 return false;
 
 }
 
}
</script>

<h1>Add Contact</h1>
<!-- login box-->
<div class="loginbox">
	<div class="loginner">
		<div class="login-left">


<div class="contacts form">
<?php echo $this->Form->create('Group', array('action'=>'addcontact','id' => 'addContactForm','onsubmit'=>'return flashvalidation11();'));?>
	<fieldset>
	<div class="feildbox" style="width:181px; margin-top: 5px">	
			<label for="some21">Groups</label>	

		
		
			<select id="KeywordId" class="txt" style="width:150px;" multiple="multiple" name="data[Group][id][]" >
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
		
		
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('phone_number');
	?>
	</fieldset>
	<br/><label>No '1' prefix, spaces, dashes, or parentheses in phone numbers</label>
<?php 
echo $this->Form->end(__('Submit', true));

?>
</div>
	</div>
	</div>
</div>