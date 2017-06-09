<style>

.feildbox img {
    border: 1px solid #C6C6C6;
    width: auto;
}
</style>
<script>

function confirm(){



 


var image= document.getElementById("image").value;


if(image==''){

alert("Please select a file.");
return false;
}

 }


</script>


<h3 class="page-title"> Upload Images</h3>
<!-- login box-->
<div>
	<div>
	
	<?php //echo $this->Html->link("Add New Contact", array('controller' => 'contacts', 'action' => 'add'), array('class' => 'forgetpass nyroModal'))?>
		<form method="post" name="imagefile" enctype='multipart/form-data' action="<?php echo SITE_URL;?>/mobile_pages/upload" onsubmit="return confirm();">
		
			<?php 
			
			
			
			
		if($imagename!=''){	
			 ?>
	<?php echo $this->Form->input('Preview', array('type' => 'textarea', 'cols' => 17,'rows'=>'7', 'escape' => false,'label'=>false,'div'=>false,'id'=>"Preview1",'readonly'=>true,'style'=>'font-size:15px;border: 1px solid #659cd0;border-radius: 5px 5px 5px 5px;width:311px;height:80px','value'=>$imagename)); ?>
	
	<?php } ?>
		<div class="feildbox">
			<label>Upload Image</label>
			<label><input id="image" type="file" name="data[image]">
			</label>
			
			</div>
			
		
			<div class="feildbox">
			<?php echo $this->Form->submit('Upload',array('div'=>false,'class'=>'btn green btn-outline'));?>
		
			</div>
			
			<div>
			
			
			</div>
	</form>
	</div>
</div>
<!-- login box-->