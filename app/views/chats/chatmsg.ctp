<script>
function deletemsg(logid){
	var r=confirm("Are you sure you want to delete?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletemsg/"+logid,
			type: "POST",
			dataType: "html",
			success: function(response){
				getnewmsgdetrails();
			}
		});
		return false;
	}
}
function retry(id){
	var r=confirm("Are you sure you want to resend?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/retry/"+id,
			type: "POST",
			dataType: "html",
			success: function(response){
				setTimeout(function(){
					getnewmsgdetrails();
				},12000);
			}
		});

	}
}
</script>
<style>
.titleName {
  border-radius: 28px;
  display: inline-block;
  height: 39px;
  line-height: 15px;
  padding: 11px;
  text-align: center;
  width: 39px;
  color: #ffffff !important;
}
#chatmsg .main-chtbx span {
  float: left;
  height: 38px;
  line-height: 17px;
  margin: 0 10px 0 10px;
  width: 38px;
}
.text-right.date a img {
  display: inline-block;
  margin: 0 0 0 5px;
  position: relative;
  text-align: right;
  top: 3px;
  width: 16px;
}
.pull-right.text-right.date > span {
    display: inline;
}
#chatmsg {
    padding: 0px 0 0;
}
.portlet.box > .portlet-title > .caption .white {
    color: white;
}
</style>
<ul class="col-lg-12 col-md-12 col-sm-12 col-xs-12 main-chtbx media-list">
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
			<!--<li class="media border-dotted <?php echo $cont_color;?>">-->
				<?php if($log['Log']['route']=='inbox'){ ?>
<li class="media border-dotted <?php echo $cont_color;?>" style="margin-bottom:10px;background:#fbfcfd">
                                        <?php if(trim($log['Contact']['color']) == '') { 
                                             $color = '#dddddd';
                                        }else {
                                             $color = $log['Contact']['color'];
                                        }?>
					<?php if($log['Contact']['email'] !=''){ ?> 
						<?php
							$url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim ( $log['Contact']['email'] ) ) ) . '?d=404';
							$headers = @get_headers( $url );
							if(preg_match( '|200|', $headers[0])){
							     echo '<img src="'.$url.'" style="width:39px;float:left;margin-left:10px" class="media-object img-circle" alt="">';
							}else if(trim($log['Contact']['name']) !='') {
echo '<span class="titleName" style="background: '.$color.' none repeat scroll 0 0;">'.strtoupper(substr($log['Contact']['name'],0,2)).'</span>';
							}else{
                                                             echo '<span class="titleName" style="background: '.$color.' none repeat scroll 0 0;">#</span>';
                                                        }
						?>
					<?php }else if(trim($log['Contact']['name']) !=''){  ?>
						<span class="titleName" style="background: <?php echo $color;?> none repeat scroll 0 0;"><?php echo strtoupper(substr($log['Contact']['name'],0,2)); ?></span>	
					<?php }else{ ?>
						<!--<span class="titleName" style="background: <?php echo $log['Contact']['color'];?> none repeat scroll 0 0;">#</span>	-->
<span class="titleName" style="background: <?php echo $color;?> none repeat scroll 0 0;">#</span>
					<?php } ?>
				<?php }else{ ?>
<li class="media border-dotted" style="margin-bottom:10px;background:#ebf9ff">
					<?php
						$fname =  substr($this->Session->read('User.first_name'),0,1);
						$lname =  substr($this->Session->read('User.last_name'),0,1);
						$name = strtoupper($fname.''.$lname);
					?>
					<!--<span class="titleName" style="background:#78beb6 none repeat scroll 0 0;"><?php echo $name; ?></span>-->
				<?php } ?>
				<!--<div class="main-chat">-->
					<?php if($log['Log']['route']=='inbox'){ ?>
                                                <div class="main-chat">
						<h3><?php if(trim($log['Contact']['name']) !=''){ echo ucfirst($log['Contact']['name']); }else{ echo $log['Contact']['phone_number']; } ?></h3>
					<?php }else{ ?>
                                                <div class="main-chat" style="padding-left:10px;float:left;width:100%">
						<h3><?php echo ucfirst($this->Session->read('User.first_name').' '.$this->Session->read('User.last_name'))?></h3>
					<?php } ?>
					<p class="pull-right text-right date">
						<?php
						$full = false;
						$now = new DateTime;
						$ago = new DateTime($log['Log']['created']);
						$diff = $now->diff($ago);
						$diff->w = floor($diff->d / 7);
						$diff->d -= $diff->w * 7;
						$string = array(
							'y' => 'year',
							'm' => 'month',
							'w' => 'week',
							'd' => 'day',
							'h' => 'hour',
							'i' => 'minute',
							's' => 'second',
						);
						if($diff->d == 0){
							foreach ($string as $k => &$v) {
								if ($diff->$k) {
									$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
								} else {
									unset($string[$k]);
								}
							}
							if (!$full) $string = array_slice($string, 0, 1);
							if($log['Log']['route']=='outbox'){
								if(($diff->s <= 5) && ($diff->i == 0)){
									echo "Sending..";
								}else if(($diff->i == 0) && ($diff->s >= 5) && ($diff->s <= 10)){
									echo ucfirst($log['Log']['sms_status']);
								}else if (trim($log['Log']['sms_status'])=='undelivered' || trim($log['Log']['sms_status'])=='failed') { ?>
									<p class="pull-right text-right date" style="color:#c0392b !important;"><?php echo ucfirst($log['Log']['sms_status']);?><span class="reficn"><a href="#null" onclick="return retry(<?php echo $log['Log']['id'];?>)"><i class="fa fa-refresh" aria-hidden="true"></i></a></span></p>
								<?php }else{
									echo date('h:i a / j M Y',strtotime($log['Log']['created']));
								}
							}else{
								echo date('h:i a / j M Y',strtotime($log['Log']['created']));
							}
						}else{
							echo date('h:i a / j M Y',strtotime($log['Log']['created']));
						}
						?>
					</p>
					<?php if($log['Log']['msg_type']=='text'){ ?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padd">
                          
				      <!--<p style=" word-wrap: break-word;"><?php echo ucfirst($log['Log']['text_message']); ?></p>-->
<?php if(trim($log['Log']['image_url'])!=''){ ?>
                                      <p style=" word-wrap: break-word;"><img src="<?php echo $log['Log']['image_url']; ?>" width="100px" height="100px"></p>
                                <?php }else {?>
				      <p style=" word-wrap: break-word;"><?php echo ucfirst($log['Log']['text_message']); ?></p>
                                <?php } ?>
                            
							<div class="div-hover">
                                                        <?php if($log['Log']['route']=='inbox'){ ?>
								<?php if($log['Log']['read']==0){?>
									<a href="#null" onclick="return markread(<?php echo $log['Log']['id'];?>)" style="font-size:13px;"><i class="fa fa-check-circle-o" style="font-size:13px;"></i>mark as read</a>
								<?php }else if($log['Log']['read']==1){?>
									<a href="#null" onclick="return markunread(<?php echo $log['Log']['id'];?>)" style="font-size:13px;"><i class="fa fa-check-circle" style="font-size:13px;"></i>mark as unread</a>
								<?php }} ?>
								<a href="#null" onclick="return deletemsg(<?php echo $log['Log']['id'];?>)" style="font-size:13px;"><i class="fa fa-trash" style="font-size:13px;"></i>delete</a>
								
							</div>
						</div>
					<?php } ?>
				</div>
			</li>
		<?php } ?>
	<?php } ?>
</ul><!--/ main-chtbx -->