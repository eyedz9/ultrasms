<?php echo $this->Form->create('User', array('action' => 'thankyou','id'=>'SignupForm')); 
?>
<p> 
	<?php echo $form->submit('Submit', array('type'=>'submit','id'=>'SaveAccount','class'=>'mid-img' ,'value'=>'Sign Up'));  ?> 
	<?php echo $form->submit('Cancel', array('type'=>'button','class'=>'mid-img cancel' ,'value'=>'Cancel'));  ?>
</p>
<?php echo $this->Form->end();?>
		
		
