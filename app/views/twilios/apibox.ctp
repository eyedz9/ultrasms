<h1>PHP API Code</h1>
<!-- login box-->
<div class="loginbox">
	<div class="loginner">
	<!--Javascript validation goes Here---------->
	<div >
		<?php echo $this->Session->Flash();?>
	</div>
		<!--Javascript validation goes Here---------->
		
	<?php //echo $this->Form->create('',array('controller'=>'twilios','action' => 'searchnumber'));?>
		<div class="login-left">
				
			<div align="center">
				<font size="5">Your API Key: <b><?php echo $apiKey;?></b></font><br>* DO NOT SHARE YOUR API KEY WITH <u>ANYONE</u>.<br><br>
				<font size="2">We have created the easiest to use PHP API for any Web Developers to date. Here is an example script to send a text message from your site or application.</font><br><br>
<textarea cols="30" rows="10">&lt;?
$apikey = "<?php echo $apiKey;?>"; // Your exact API Key				
$themessage = $_POST['message']; // The text message to send				
$sendto = $_POST['phone']; // Send to this number, numbers ONLY

// dont edit anything below unless youre familiar with PHP
$encoded = base64_encode($themessage);
$replacewith = array("+", "=");
$replacers   = array("7PLUS7", "7EQUALS7");
$themsg = str_replace($replacewith, $replacers, $encoded);
$pingapi = file_get_contents("<?php  echo SITE_URL ?>/twilios/apicall/$apikey/$sendto/$themsg");
if ( $pingapi == "sent" ) {
echo "Message Sent!";
} else {
echo "Message not sent (&lt;b&gt;$pingapi&lt;/b&gt;)";
}
?&gt;</textarea><br>
				
</div>
		</div>		
	</div>
</div>
<!-- login box-->

