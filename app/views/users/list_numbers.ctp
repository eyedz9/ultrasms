<h3>Choose a Phone number to buy</h3>
			<div style="overflow: scroll;height:300px;width:350px;" class="users form">
				<?php foreach($AvailablePhoneNumbers as $number): ?>
				<form method="POST">
					<label><?php echo $number->FriendlyName ?></label>
					<input type="hidden" name="PhoneNumber" value="<?php echo $number->PhoneNumber ?>">
					<input type="button" style="margin-left:20px;" class="buttonClass" name="button" value="BUY"  onClick="buythisnumber(<?php echo $number->PhoneNumber ?>)"/>
				</form>
				<?php endforeach; ?>
			</div>	