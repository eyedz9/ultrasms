<div id="index_form">

				<h2 class="login">Administration Login</h2>
				<div class="loginbox">
				<form method="post" action="<?PHP echo $html->url('/admin_users/login') ?>" style="width:55%">	
					<?php echo $form->input('username'); ?> 
					<div class="input">
					<label for="password1">Password</label> 
					<?php echo $form->password('password'); ?>
					</div>
					</div>
					<?php echo  $form->end('Submit');?></form>
</div>
