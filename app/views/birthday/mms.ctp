<style>
.loginbox a.forgetpass {
    background: none repeat scroll 0 0 #3d794e;
    border-radius: 10px 10px 10px 10px;
    color: #FFFFFF;
    float: left;
    font-size: 11px;
    font-weight: normal;
    margin: 0 5px 0 0;
    padding: 2px 10px;
}

</style>
<style type="text/css">

	
		
	/*
	Pretty Table Styling
	CSS Tricks also has a nice writeup: http://css-tricks.com/feature-table-design/
	*/
	
	table {
		overflow:hidden;
		border:1px solid #d3d3d3;
		background:#fefefe;
		width:100%;
		margin-bottom: 10px;
		-moz-border-radius:0px; /* FF1+ */
		-webkit-border-radius:0px; /* Saf3-4 */
		border-radius:0px;
		-moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
	}
	
	th, td {padding:18px 28px 18px;  }
	
	th {padding-top:22px; text-shadow: 1px 1px 1px #fff; background:#e8eaeb; vertical-align:bottom;}
	
	td {border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0; vertical-align:top; }
	
	tr.odd-row td {background:#f6f6f6;}
	
	td.first, th.first {text-align:left}
	
	td.last {border-right:none;}
	
	/*
	Background gradients are completely unnecessary but a neat effect.
	*/
	
	td {
		background: -moz-linear-gradient(100% 25% 90deg, #fefefe, #f9f9f9);
		background: -webkit-gradient(linear, 0% 0%, 0% 45%, from(#ecf9ff), to(#fefefe));
	}
	
	tr.odd-row td {
		background: -moz-linear-gradient(100% 25% 90deg, #f6f6f6, #f1f1f1);
		background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f1f1f1), to(#f6f6f6));
	}
	
	th {
		background: -moz-linear-gradient(100% 20% 90deg, #e8eaeb, #ededed);
		background: -webkit-gradient(linear, 0% 0%, 0% 90%, from(#FDAF07), to(#FFE7B4));
	}
	
	/*
	I know this is annoying, but we need additional styling so webkit will recognize rounded corners on background elements.
	Nice write up of this issue: http://www.onenaught.com/posts/266/css-inner-elements-breaking-border-radius
	
	And, since we've applied the background colors to td/th element because of IE, Gecko browsers also need it.
	*/
	
	tr:first-child th.first {
		-moz-border-radius-topleft:5px;
		-webkit-border-top-left-radius:5px; /* Saf3-4 */
	}
	
	tr:first-child th.last {
		-moz-border-radius-topright:5px;
		-webkit-border-top-right-radius:5px; /* Saf3-4 */
	}
	
	tr:last-child td.first {
		-moz-border-radius-bottomleft:5px;
		-webkit-border-bottom-left-radius:5px; /* Saf3-4 */
	}
	
	tr:last-child td.last {
		-moz-border-radius-bottomright:5px;
		-webkit-border-bottom-right-radius:5px; /* Saf3-4 */
	}

</style>
<h2>Auto Responders</h2>
<!-- login box-->
<div class="loginbox">

<ul class="secondary_nav">
<li><a class="current" href="/smsdemo/responders">SMS Message</a></li>
<li><a href="/smsdemo/responders/mms">MMS Messages</a></li>
			
</ul>


	<div class="loginner">
	<div style="text-align:right;float:right; margin-top: -3px;padding-bottom: 5px">
	<?php //echo $this->Html->link("Add Group", array('controller' => 'groups', 'action' => 'add'), array('class' => 'forgetpass'))?>
       <a href="<?php echo SITE_URL;?>/responders/add_mms" title="Add Responder"><img src="<?php echo SITE_URL;?>/img/add_responder.png"></a>
		
	</div>
	<table cellpadding="0" cellspacing="0" width="100%">
	
	<tr>
			<th>Group Name</th>
			
            <th><?php echo $this->Paginator->sort('days');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<!--<th># inside()</th>-->
			<th>MMS</th>
			
			
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($responders as $responder):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
	    <td><?php echo $responder['Group']['group_name'];?>&nbsp;</td>
               <td><?php echo $responder['Responder']['days'];?>&nbsp;</td>
		<td><?php echo ucfirst($responder['Responder']['name']);?>&nbsp;</td>
		<td><img src="<?php echo $responder['Responder']['image_url'];?>" width="50px" height="50px;""/>&nbsp;</td>
		
		
		<td class="actions">
			
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit_mms', $responder['Responder']['id']),array('class' => 'buttons')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $responder['Responder']['id'],'1'), array('class' => 'buttonstyle'), sprintf(__('Are you sure you want to delete this responders?', true))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<!div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
	</div>
</div>
<!-- login box-->