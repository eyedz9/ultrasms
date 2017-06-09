<div class="changepassword">
<?php
echo $form->create('AdminUser',array('action'=>'change_password'));
if($session->check('AdminUser'))
	$admin=$session->read('AdminUser');
echo $form->hidden('username',array('value'=>$admin['username']));
echo $form->input('Old password');
echo $form->input('New Password',array('type'=>'password'));
echo $form->input('New Password Again',array('type'=>'password'));
echo $form->end('Submit');
?>
</div>
