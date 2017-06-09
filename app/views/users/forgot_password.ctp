<style>
.error-message{color:red;}
.message{color:red;
}
</style>
        <div class="menu-toggler sidebar-toggler"></div>
        <div class="logo">
            <?php echo $this->Html->link($this->Html->image(LOGO,array('class' =>'img-responsive')), '/', array('escape' =>false, 'class' =>'brand'));?>
        </div>
    <div class="content">
		<div id="validationMessages" style="display:none"></div>
		<?php echo $this->Session->flash(); ?>
		   <h3 class="font-green">Forget Password ?</h3>
                <p>Please enter your e-mail address below, then click Send.</p>
            <form method="post" action="" class="forget-form" novalidate="novalidate" style="display: block;">
             
				<!--p> You will receive your password shortly.</p-->
					<div class="form-group">					
						<?php echo $this->Form->input('User.email',array('div'=>false,'label'=>false, 'class' => 'form-control placeholder-no-fix','placeholder'=>"Email"))?>				
					</div>
					<div class="form-actions">
						<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn green pull-right'));?>
						<?php echo $this->Html->link('Back', array('controller' => 'users', 'action' =>'login'), array('class' =>'btn red btn-outline'))?>
					</div>
					<div class="create-account">
						<p>                      
							<?php echo $this->Html->link('Create an account',array('controller' =>'users', 'action' => 'add'), array('escape' =>false,'class'=>'uppercase'))?>
						</p>
					</div>
			   <?php echo $this->Form->end();
				echo $this->Validation->rules(array('User'),array('formId'=>'loginForm','validationBlock' =>'Userlogin'));?>
    </div>
         <div class="copyright"> <?php echo date('Y')?> © <?php echo SITENAME;?>. </div>
 