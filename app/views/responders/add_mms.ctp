<script>
 /* <![CDATA[ */
            jQuery(function(){
			 jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter name"
                });jQuery("#days").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter days"
                });jQuery("#ResponderGroupId").validate({
                    expression: "if (VAL > 0) return true; else return false;",
                    message: "Please select group"
                });jQuery("#message").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter message"
                });
            });
            /* ]]> */			
</script>
<script type="text/javascript">
 $(document).ready(function (){
$('textarea[maxlength]').live('keyup change', function() {
  var str = $(this).val()
  var mx = parseInt($(this).attr('maxlength'))
  if (str.length > mx) {
     $(this).val(str.substr(0, mx))
     return false;
  }
  }
  )
});


</script>
<script>


var count = "148";
function update(){
var tex = $("#message").val();


var count1 = (148);

tex = tex.replace('{','');
tex = tex.replace('}','');
tex = tex.replace('[','');
tex = tex.replace(']','');
tex = tex.replace('~','');
tex = tex.replace(';','');
tex = tex.replace('`','');tex = tex.replace('"','');
var len = tex.length;




//var message = $("#Preview").val();
//var lenth = msg.length;


$("#message").val(tex);
if(len > count){
tex = tex.substring(0,count1);

return false;
}



$("#limit").val(count-len);

}


 function confirmpagemessage(id){
  //alert(id);
  
 var messageview= $('#message').val();
   //alert(mesage);
  
 if(id>0){

 $.ajax({
 
  url: "<?php  echo SITE_URL ?>/messages/mobile_pages/"+id,
  
  type: "POST",
 
  dataType: "html",
  
  
  success: function(response) {
  
  if(messageview!=''){
  
  
   $('#message').val(response);
   return;
  
  }else{
  
  $('#message').html(response);

  }
 }
 
 });


}

}

function confirmmessage(id){
 
  
 var message= $('#message').val();
   //alert(mesage);
  
 if(id>0){

 $.ajax({
 
  url: "<?php  echo SITE_URL ?>/messages/checktemplatedata/"+id,
  type: "POST",
 
  dataType: "html",
  
  success: function(response) {
  
  
  if(message!=''){

    $('#message').val(response);

   
   return;
  
  }else{

  $('#message').html(response);

  }
 }
 
 });


}

}


</script>

<script type="text/javascript">

function popmessagepickwidget(value){

$('#message').val(value);

}
</script>

<style>

/*.loginbox .inputbutton {
    background: none repeat scroll 0 0 #2E6F7B;
    border: medium none;
    border-radius: 4px 4px 4px 4px;
    color: #FFFFFF;
  float: none;
    font-size: 12px;
    font-weight: bold;
    padding: 5px 15px;
    text-transform: uppercase;
}*/
.ValidationErrors{

color:red;

}
</style>

<h1>Add Responder</h1>


<div class="loginbox">
	<div class="loginner">
		<?php echo $this->Form->create('Responder',array('action'=> 'add_mms','enctype'=>'multipart/form-data'));?>
		
		<div class="feildbox">
				<label>Group<span class="required_star">*</span></label>
				<?php
	
	$Group[0]='Select Group';
    echo $this->Form->input('Responder.group_id', array(
    'style'=>'width:261px;',
    'label'=>false,
    'type'=>'select',
    'default'=>0,
    'options' => $Group));
	
	?>

			</div>

	<div class="feildbox">
				<label>Name<span class="required_star">*</span></label>
				<?php echo $this->Form->input('Responder.name',array('div'=>false,'label'=>false, 'class' => 'inputtext','id'=>'name'))?>
		</div>			

		<div class="feildbox">
				<label>Message Template</label>
				<?php
	
	$Smstemplate[0]='Select Message Template';
    echo $this->Form->input('Smstemplate.id', array(
    'style'=>'width:261px;',
    'label'=>false,
    'default'=>0,
    'type'=>'select',
	'onchange'=>'confirmmessage(this.value)',
    'options' => $Smstemplate));
	
	?>

			</div> 
					<div class="feildbox">
				<label>Mobile Splash Page<span class="required_star"></span></label>
				<select id="mobilespagesId" class="txt" style="width:261px;"  name="data[mobilespages][id]" onchange="confirmpagemessage(this.value)">
				<?php
		$mobilespages[0]="Select Mobile Splash Page";	
	foreach($mobilespages as $row => $value){
    $selected = '';
	
	
	
    if($row == $mobilepageid){
        $selected = ' selected="selected"';
   }?>
   <option "<?php echo  $selected; ?> " value="<?php echo  $row; ?>"><?php echo  $value; ?></option>
<?php }
	?>
		</select>	


		
			</div>	
			
			<?php if (FILEPICKERON == 'Yes'){?>
	<input onchange="popmessagepickwidget(event.fpfile.url)" data-fp-container="modal" data-fp-mimetypes="*/*" data-fp-apikey=<?php echo FILEPICKERAPIKEY?> type="filepicker">	
	<?php } ?>
	
			
			
			<!--div class="feildbox">
				<label>Responder Message<span class="required_star">*</label>
<?php //echo $this->Form->input('Responder.message',array('div'=>false,'label'=>false, 'class' => 'textarea','id'=>'message','maxlength'=>'160','rows'=>'6','cols'=>46,'onKeyup' =>'return update()'))?>
<div id='counter'>Remaining Characters<script type="text/javascript">
document.write("<input type=text  name=limit id=limit size=4 readonly value="+count+">");
</script></div>
Special characters not allowed such as ~ { } [ ] ;
<span id="messageErr" class="ValidationErrors"></span>
		</div-->
		
		<div class="feildbox">
				<label>Responder Message<span class="required_star">*</label>
		<input type="file" name="data[message]" name="mms"> 		
		</div>	

	<div class="feildbox">
	<label>Appended to message<span class="required_star"></label>
<?php echo $this->Form->input('Responder.systemmsg',array('div'=>false,'label'=>false, 'class' => 'textarea','id'=>'message','maxlength'=>'148','rows'=>'3','cols'=>46,'value'=>'STOP to end','readonly'=>'readonly'))?>

		</div>			
		
		<div class="feildbox">
				<label>Send # of days after signup - Use 1 or greater:<span class="required_star">*</span></label>
				<?php echo $this->Form->input('Responder.days',array('div'=>false,'label'=>false, 'class' => 'inputtext','id'=>'days'))?>
		</div>
			
		
		<div class="feildbox">
			<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'inputbutton'));?>
			<?php echo $this->Html->link(__('Cancel', true), array('controller' => 'responders','action' => 'index'),array('class'=>'inputbutton','style'=>'margin:0px;')); ?>
			
			</div>
		
		
		
			<?php echo $this->Form->end(); ?>
	
	</div>
</div>