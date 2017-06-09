<h1>User Permissions</h1>
<div>
	<div>
        <?php echo $this->Form->create('users',array('action'=>'userpermissions/'.$id.'/1'));?>

	<table id="tableOne" cellspacing="0" cellpadding="0"style="width:450px" >
		<thead>
		<tr>
		
			<th class="tc">Feature</th>
			<th class="tc">Access</th>
		
		</tr>
				
	    </thead>
		 <tbody>		
		
	           <tr> 
			 
			<td width="350px">Autoresponders</td>
			<td width="350px" style="align:right">
<input type="checkbox" name="data[User][autoresponders]" value="1" <?php if($userpermissions['User']['autoresponders']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr class="altrow"> 
			 
			<td>Import Contacts</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][importcontacts]" value="1" <?php if($userpermissions['User']['importcontacts']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr> 
			 
			<td>Short Links</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][shortlinks]" value="1" <?php if($userpermissions['User']['shortlinks']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr class="altrow"> 
			 
			<td>Voice Broadcast</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][voicebroadcast]" value="1" <?php if($userpermissions['User']['voicebroadcast']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr> 
			 
			<td>Polls</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][polls]" value="1" <?php if($userpermissions['User']['polls']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr class="altrow"> 
			 
			<td>Contests</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][contests]" value="1" <?php if($userpermissions['User']['contests']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr> 
			 
			<td>Loyalty Programs</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][loyaltyprograms]" value="1" <?php if($userpermissions['User']['loyaltyprograms']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr class="altrow"> 
			 
			<td>Kiosk Builder</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][kioskbuilder]" value="1" <?php if($userpermissions['User']['kioskbuilder']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr> 
			 
			<td>Birthday SMS Wishes</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][birthdaywishes]" value="1" <?php if($userpermissions['User']['birthdaywishes']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr class="altrow"> 
			 
			<td>Mobile Page Builder</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][mobilepagebuilder]" value="1" <?php if($userpermissions['User']['mobilepagebuilder']==1){?> checked <?php } ?>/>


</td>
</tr>
<tr> 
			 
			<td>Web Sign-up Widgets</td>
			<td style="text-align:center;">
<input type="checkbox" name="data[User][webwidgets]" value="1" <?php if($userpermissions['User']['webwidgets']==1){?> checked <?php } ?>/>


</td>
</tr>
			  
			 
               </tbody>
        </table><br/>
<div class="submit" style="margin-top:-3px">
<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'inputbutton'));?>
</div>
</form>
	</div>
</div>