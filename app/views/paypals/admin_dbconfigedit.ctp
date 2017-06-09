<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List DbConfig' => '/admin/paypals/dbconfig',
						'Edit DbConfig' => '/admin/paypals/dbconfigedit/1'
					   					   
				);				
				$matchingLinks = array();
				
				foreach ($navigation as $link) {
						if (preg_match('/^'.preg_quote($link, '/').'/', substr($this->here, strlen($this->base)))) {
								$matchingLinks[strlen($link)] = $link;
						}
				}
				
				krsort($matchingLinks);
				
				$activeLink = ife(!empty($matchingLinks), array_shift($matchingLinks));
				$out = array();
				
				foreach ($navigation as $title => $link) {
						$out[] = '<li>'.$html->link($title, $link, ife($link == $activeLink, array('class' => 'current'))).'</li>';
				}
				
				echo join("\n", $out);
				?>			
</ul>
<div class="configs form">
<?php echo $this->Form->create('Paypal');?>
	<fieldset>
		<legend><?php __('Edit DB Config'); ?></legend>
		
	<?php
	
	    
		echo $this->Form->input('Dbconfig.id',array('type'=>'hidden','value'=>$id));
echo $this->Form->input('Dbconfig.dbname');
		echo $this->Form->input('Dbconfig.dbusername');
		
		echo $this->Form->input('Dbconfig.dbpassword');           
		?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>