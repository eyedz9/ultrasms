<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			Paypal Email
		</div>
	</div>
	<div class="portlet-body">
		<form role="form">
			<div class="form-body">
				<?php echo $this->Form->create('User',array('controller'=>'users','action' => 'change_paypal_email','id' =>'paypalEmailForm'))?>
				<div class="form-group">
				<!--Javascript validation goes Here---------->
					<div id="validationMessages" class="form-control" style="display:none"></div>
				<!--Javascript validation goes Here---------->
				<?php echo $this->Form->input('paypal_email', array('class'=>'form-control','value' =>$user['User']['paypal_email']))?>
				</div>
				<br>
			</div>
			<div class="form-actions">	 
				<?php echo $this->Form->submit('Update',array('div'=>false,'class'=>'btn blue'));?>
			</div>
			<?php 
			echo $this->Form->end(); ?>
		</form>
			<?php echo $this->Validation->rules(array('User'),array('formId'=>'paypalEmailForm','validationBlock' =>'ChangePaypalEmail'));
			?>
	</div>
</div>
