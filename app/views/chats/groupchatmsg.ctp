<script>
function deletethread(group_id){
	var r=confirm("Are you sure you want to delete ?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletegroup_thread/"+group_id,
			type: "POST",
			dataType: "html",
			success: function(response){
				getnewmsg_group();
			}
		});
	}
}



function deletemsggroup(id){
	var r=confirm("Are you sure you want to delete ?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletemsggroup/"+id,
			type: "POST",
			dataType: "html",
			success: function(response){
				getnewmsg_group();
			}
		});
	}
}
</script>
<ul class="col-lg-12 col-md-12 col-sm-12 col-xs-12 main-chtbx">
		<?php if(!empty($logs)){?>
			<?php 
			$i=1; 
			foreach($logs as $log){ 
				if($i%2==0){	
					$cont_color="white";
					$date_black="black";
				}else{
					$cont_color="";	
					$date_black="";
				}
				$i++;
			?>
			<li class="media border-dotted <?php echo $cont_color;?>">
				<?php if($this->Session->read('User.email') !=''){ 
					$url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim ( $this->Session->read('User.email') ) ) ) . '?d=404';
					$headers = @get_headers( $url );
					if(preg_match( '|200|', $headers[0])){ ?>
						<span><img class="media-object img-circle" style="width: 39px;" src="<?php echo $url;?>" alt=""/></span>
					<?php }else{ 
						$fname =  substr($this->Session->read('User.first_name'),0,1);
						$lname =  substr($this->Session->read('User.last_name'),0,1);
						$name = strtoupper($fname.''.$lname);
						?>
						<span class="titleName" style="background:#78beb6 none repeat scroll 0 0;"><?php echo $name; ?></span>
					<?php }	?>
				<?php }else{ ?>
					<span><i class="fa fa-users" aria-hidden="true" style="font-size:20px;"></i></span>
				<?php } ?>
				<div class="main-chat">
					<h3><?php echo ucfirst($this->Session->read('User.first_name').' '.$this->Session->read('User.last_name'))?></h3>
					<p class="pull-right text-right date">
					<?php echo date('j M Y',strtotime($log['GroupSmsBlast']['created'])); ?>
					
					</p>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padd">
						<p style=" word-wrap: break-word;"><?php echo ucfirst($log['GroupSmsBlast']['msg']); ?></p>
						<p>
						<?php if(isset($log['Log'][0])){?>
							<?php if($log['Log'][0]['image_url'] !=''){?>
								<?php 
								$images_url = explode(',',$log['Log'][0]['image_url']);
								foreach($images_url as $images_urls){
								?>
								<img width='100px' height='100px' src="<?php echo $images_urls;?>">
								<?php } ?>
							<?php } ?>
						<?php } ?>
						</p>
						<div class="div-hover">
							<a href="#null" onclick="return deletemsggroup(<?php echo $log['GroupSmsBlast']['id'];?>)"><i class="fa fa-circle"></i>delete</a>
						</div>
					</div>
				</div>
			</li>
		<?php } ?>
	<?php } ?>
</ul><!--/ main-chtbx -->