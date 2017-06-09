
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
<style>



/* Tooltip */

#tooltip {
	position:absolute;
	z-index:9999;
	color:#fff;
	font-size:12px;
	width:300px; top:100px; left:200px;
	
}

#tooltip .tipHeader {
	height:8px;
	background:url(images/tipHeader.gif) no-repeat;
}


#tooltip .tipBody {
	background-color:#a50a15;
	padding:5px 5px 5px 15px; border-radius:10px; border:1px solid #FF0000;
}

#tooltip .tipFooter {
	height:8px;
	background:url(images/tipFooter.gif) no-repeat;
}

.feildbox img {
    border: none;
    width: auto;
    
}

.loginbox  .inputbutton {
  -pie-background: linear-gradient(#636363, #000);
  behavior: url(PIE.php);
  background: #292929;
  background: -webkit-gradient(linear, left top, left bottom, from(#636363), to(#000));
  background-image: -o-linear-gradient(top,#636363,#000);
  background-image: linear-gradient(#636363  20%, #000 100%);
  background-image: -webkit-linear-gradient(#636363  20%, #000 100%);
  background-image: -moz-linear-gradient(#636363  20%, #000 100%);
  background-image: -ms-linear-gradient(#636363  20%, #000 100%);
  filter: progid:DXImageTransform.Microsoft.Gradient(startColorstr='#636363', endColorstr='#000',GradientType=0);
  background-position:initial initial;
  background-repeat:initial initial;
  border:1px solid #0E0E0E;
  -webkit-box-shadow:rgba(255, 255, 255, 0.2) 0 1px 0 0 inset, rgba(0, 0, 0, 0.0980392) 1px 1px 1px;
  -moz-box-shadow:rgba(0, 0, 0, 0.0980392) 0 2px 3px, rgba(255, 255, 255, 0.6) 0 1px 0 inset;
  box-shadow:rgba(255, 255, 255, 0.2) 0 1px 0 0 inset, rgba(0, 0, 0, 0.0980392) 1px 1px 1px;
  color:#FFFFFF !important;
  text-shadow:#000000 -1px -1px 0;
  text-decoration:none;
  padding: 5px 10px 5px 10px;
  margin-left: 145px;
}

.loginbox  .inputbutton:hover {
  -pie-background: linear-gradient(#000000, #636363);
  behavior: url(PIE.php);
  background: #292929;
  background: -webkit-gradient(linear, left top, left bottom, from(#000000), to(#636363));
  background-image: -o-linear-gradient(top,#000000,#636363);
  background-image: linear-gradient(#000000 20%, #636363 100%);
  background-image: -webkit-linear-gradient(#000000 20%, #636363 100%);
  background-image: -moz-linear-gradient(#000000 20%, #636363 100%);
  background-image: -ms-linear-gradient(#000000 20%, #636363 100%);
  filter: progid:DXImageTransform.Microsoft.Gradient(startColorstr='#000000', endColorstr='#636363',GradientType=0);
  background-position:initial initial;
  background-repeat:initial initial;
  border:1px solid #0E0E0E;
  -webkit-box-shadow:rgba(255, 255, 255, 0.2) 0 1px 0 0 inset, rgba(0, 0, 0, 0.0980392) 1px 1px 1px;
  -moz-box-shadow:rgba(0, 0, 0, 0.0980392) 0 2px 3px, rgba(255, 255, 255, 0.6) 0 1px 0 inset;
  box-shadow:rgba(255, 255, 255, 0.2) 0 1px 0 0 inset, rgba(0, 0, 0, 0.0980392) 1px 1px 1px;
  color:#FFFFFF !important;
  text-shadow:#000000 -1px -1px 0;
  text-decoration:none;
  padding: 5px 10px 5px 10px;
  margin-left: 145px;
 
}

</style>

<!-- login box-->
<div class="loginbox">
	<div class="loginner">
	
	<?php //echo $this->Html->link("Add New Contact", array('controller' => 'contacts', 'action' => 'add'), array('class' => 'forgetpass nyroModal'))?>
		<form method="post" name="imagefile" enctype='multipart/form-data' action="<?php echo SITE_URL;?>/webwidgets/upload" onsubmit="return confirm();">
		<?php 
			
			
			
			
		if($imagename!=''){	
			 ?>
	<?php echo $this->Form->input('Preview', array('type' => 'textarea', 'cols' => 17,'rows'=>'7', 'escape' => false,'label'=>false,'div'=>false,'id'=>"Preview1",'readonly'=>true,'style'=>'font-size:15px;border: 1px solid #659cd0;border-radius: 5px 5px 5px 5px;width:223px;height:97px','value'=>$imagename)); ?>
	
	<?php } ?>

		<div class="feildbox">
			<label>Upload Image</label>
			<label><input id="image" type="file" name="data[image]">
			</label>
			
			</div>
			
		
			<div class="feildbox">
			<?php echo $this->Form->submit('Upload',array('div'=>false,'class'=>'inputbutton'));?>
		
			</div>
			
			<div>
			
			
			</div>
	</form>
	</div>
</div>
<!-- login box-->