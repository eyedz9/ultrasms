<style>
.error-message{color:red;}
.message{color:red;
</style>

<script type="text/javascript">
    function submitOnEnter(inputElement, event) {
        if (event.keyCode == 13) { 
            inputElement.form.submit();
        }
    }
</script>

<div class="menu-toggler sidebar-toggler"></div>
        <div class="logo">
            <?php echo $this->Html->link($this->Html->image(LOGO,array('class' =>'img-responsive')), '/', array('escape' =>false, 'class' =>'brand'));?>
        </div>
<div class="content">
<div id="validationMessages" style="display:none"></div>
<?php echo $this->Session->flash(); ?>
    <?php echo $this->Form->create('User',array('action'=> 'login','name'=>'loginForm','id'=>'loginForm'));?>
        <h3 class="form-title">Login to your account</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span> Enter any username and password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<!--label class="control-label visible-ie8 visible-ie9">Username</label-->
			   <div class="input-icon">
				<i class="fa fa-user"></i>
				<?php echo $this->Form->input('User.usrname',array('div'=>false,'label'=>false, 'class' => 'form-control placeholder-no-fix','placeholder'=>"User Name",'required'))?>
			   </div>
		</div>
		<div class="form-group">
			<!--label class="control-label visible-ie8 visible-ie9">Password</label-->
			<div class="input-icon">
			 <i class="fa fa-lock"></i>
			 <?php echo $this->Form->input('User.passwrd',array('type' =>'password', 'onkeypress'=>'submitOnEnter(this, event);','div'=>false,'label'=>false, 'class' => 'form-control placeholder-no-fix','placeholder'=>"Password",'required'))?>
			</div>
		</div>
		<div class="form-actions">
			<!--<label class="checkbox">
			<input type="checkbox" name="remember" value="1" /> Remember me </label>-->
			<?php echo $this->Form->submit('Login',array('div'=>false,'class'=>'btn green pull-right'));?>	
		</div>	
		<!--div class="login-options">
			<h4>Or login with</h4>
			<ul class="social-icons">
				<li>
					<a class="facebook" data-original-title="facebook" href="javascript:;"> </a>
				</li>
				<li>
					<a class="twitter" data-original-title="Twitter" href="javascript:;"> </a>
				</li>
				<li>
					<a class="googleplus" data-original-title="Goole Plus" href="javascript:;"> </a>
				</li>
				<li>
					<a class="linkedin" data-original-title="Linkedin" href="javascript:;"> </a>
				</li>
			</ul>
		</div-->
		<div class="forget-password">
			<h4>Forgot your password ?</h4>
			<p> no worries, click
			<?php echo $this->Html->link('Forgot Password?', array('controller' => 'users', 'action' =>'forgot_password'), array('class' =>'forget-password'))?>
			</p>
		</div>
		<div class="create-account">
		    <p> Don't have an account yet ?&nbsp;
			<?php echo $this->Html->link('Create an account',array('controller' =>'users', 'action' => 'add'), array('escape' =>false,'class'=>'uppercase'))?>
			</p>
		</div>
	<?php echo $this->Form->end();
	echo $this->Validation->rules(array('User'),array('formId'=>'loginForm','validationBlock' =>'Userlogin'));?>
</div>
<div class="copyright"> <?php echo date('Y')?> © <?php echo SITENAME;?>. </div>