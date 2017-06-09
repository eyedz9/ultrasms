	<h1>Number List</h1>
<div class="loginbox">
	<div class="loginner">
      
	<table id="tableOne" cellspacing="0" cellpadding="0"style="width:100%;" >
		<thead>
		<tr>
		
			<th class="tc">Number</th>
			<th class="tc">Type</th>
			<?php if(API_TYPE!=2){ ?>
			<th class="tc">Action</th>
			<?php } ?>
			
			
			
		</tr>
				
	    </thead>
		 <tbody>		
		<?php 
		if(!empty($numbers)){
		$i = 0;
		foreach($numbers as $invoicedetil) { 
		$class = null;
		
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
		?>
	
	<tr <?php echo $class;?>> 
			 
			   <td style="text-align:center;"><?php echo $invoicedetil['UserNumber']['number'] ?></td>
			   <td style="text-align:center;" >Secondary</td>
			   <?php if(API_TYPE!=2){ ?>
	           <td style="text-align:center;" class="actions"><?php echo $this->Html->link(__('Release Number', true), array('action' => 'number_release', $invoicedetil['UserNumber']['id']), array('class' => 'forgetpass'), sprintf(__('Are you sure you want to release this number for this user?', true)));  ?></td> </tr>
				<?php } ?>
			 
			 
			  
			  <?php }} ?>
			  
			  <?php 
			  
			  if($usernumbers['User']['assigned_number'] > 0){ ?>
			 </tr>  
			 <td style="text-align:center;"><?php echo $usernumbers['User']['assigned_number'] ?></td>
			   <td style="text-align:center;">Primary</td>
			    <?php if(API_TYPE!=2){ ?>
	         <td style="text-align:center;" class="actions"><?php echo $this->Html->link(__('Release Number', true), array('action' => 'number_release_user', $usernumbers['User']['id']), array('class' => 'forgetpass'), sprintf(__('Are you sure you want to release this number for this user?', true)));  ?></td> </tr>
			  <?php } ?>
			  <?php } ?>
			 
			  </tbody>
</table>

	</div>
</div>
	
		
		