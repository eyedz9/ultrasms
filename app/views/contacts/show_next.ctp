<?php 
	echo $this->Html->script('jQvalidations/jquery.validation.functions');
	echo $this->Html->script('jQvalidations/jquery.validate');
?>
<style>
.ValidationErrors{
color:red;
}
</style>
<script>
 /* <![CDATA[ */
	jQuery(function(){
	 jQuery("#GroupId").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please Choose Group"
		});jQuery("#name").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please Choose name"
		});jQuery("#phone").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please Choose phone"
		});
	});
</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Import Contacts</h3>
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="icon-settings font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> Import Contacts </span>
				</div>					
			</div>
			<div class="portlet-body form">
				<?php echo $this->Form->create('Contact',array('action'=> 'check_csvdata','name'=>'loginForm','id'=>'loginForm','enctype'=>'multipart/form-data','method'=>'post'));?>
					<div class="form-body">
						<div class="form-group">
							<label for="exampleInputPassword1">Group Name</label>
							<?php 
								echo $this->Form->input('Group.id', array(
								'div'=>false,
								'label'=>false,
								'class'=>'form-control',
								'default'=>0,
								'multiple'=>true,
								'onchange'=>'group(this.value)',	
								'options' => $Group));
							?>
						</div>
						<div class="form-group">
							<label>Name</label>
							<?php echo $this->Form->select('name', $header,null, array('label' => false, 'empty' => 'Select Name','id'=>'name','class'=>'form-control'));?>
						</div>	
						<div class="form-group">
							<label>Phone</label>
							<?php echo $this->Form->select('phone', $header ,null, array('label' => false, 'empty' => 'Select Phone','id'=>'phone','class'=>'form-control'));?>
						</div>						
					</div>
					<div class="form-actions">
						<?php echo $this->Form->submit('Next',array('div'=>false,'class'=>'btn blue'));?>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>		
		</div>
	</div>
</div>
<div class="clearfix"></div>