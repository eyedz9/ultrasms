<style>
.titleName {
  border-radius: 28px !important;
  display: inline-block;
  height: 39px;
  line-height: 15px;
  padding: 11px;
  text-align: center;
  width: 39px;
  color: #ffffff !important;
}
.date-color{ color: #a3a0a0 !important; font-family: roboto; font-size: 12px!important; width: 100%; margin: 2px 0 5px !important; 
}
.portlet.light > .portlet-title > .caption .white {
    color: grey;
}

.inbx-list .lft-img .star-img .red {
    color: red;
}
</style>
<ul class="media-list">
<?php if(isset($contacts)){?>
	<?php if(!empty($contacts)){ ?>
		<?php $i=1; ?>
		<?php foreach($contacts as $contact){ ?>
		<?php if($i%2==0){	
				$cont_color="white";
			}else{
				$cont_color="";
			}
		?>
			<?php if(isset($contact['Contact']['id'])){ ?>
				<li class="media border-dotted <?php echo $cont_color;?>" id="contact_list<?php echo $contact['Contact']['id'];?>">
					<div class="lft-img">
						<span class="pull-left">
							<span style="width:20px;background:none" id="favoritefun<?php echo $contact['Contact']['id'];?>" href="#null" title="Mark/Unmark as Favorite" onclick="return favoriteassign(<?php echo $contact['Contact']['id'];?>,<?php echo $contact['Contact']['favorite'];?>)">
								<?php if($contact['Contact']['favorite']==1){ ?>
									<!--<span style="background:none" onclick="return favoriteassign(<?php echo $contact['Contact']['id'];?>,<?php echo $contact['Contact']['favorite'];?>)" class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-heart blue" aria-hidden="true"></i></span>-->
<span class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-heart red" aria-hidden="true"></i></span>
								<?php }else if($contact['Contact']['favorite']==0){ ?>
									<!--<span style="background:none" onclick="return favoriteassign(<?php echo $contact['Contact']['id'];?>,<?php echo $contact['Contact']['favorite'];?>)" class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-star white" aria-hidden="true"></i></span>-->
<span class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-heart white" aria-hidden="true"></i></span>
								<?php } ?>
							</span>
							<span style="position: relative;" onclick="return chat('CONTACT',<?php echo $contact['Contact']['id'];?>)">
							<?php if($contact['Contact']['email'] !=''){ ?> 
								<?php
									$url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim ( $contact['Contact']['email'] ) ) ) . '?d=404';
									$headers = @get_headers( $url );
									if(preg_match( '|200|', $headers[0])){
										echo '<img src="'.$url.'" style="width:39px;" class="media-object img-circle" alt="">';
									}else if(trim($contact['Contact']['name']) !=''){
										echo '<span class="titleName" style="background: '.$contact['Contact']['color'].' none repeat scroll 0 0;">'.strtoupper(substr(trim($contact['Contact']['name']),0,2)).'</span>	';
									}else{
										echo '<span class="titleName" style="background: '.$contact['Contact']['color'].' none repeat scroll 0 0;">#</span>	';
									}
								?>
							<?php }else if(trim($contact['Contact']['name']) !=''){  ?>
								<span class="titleName" style="background: <?php echo $contact['Contact']['color'];?> none repeat scroll 0 0;"><?php echo strtoupper(substr(trim($contact['Contact']['name']),0,2)); ?></span>	
							<?php }else{ ?>
								<span class="titleName" style="background: <?php echo $contact['Contact']['color'];?> none repeat scroll 0 0;">#</span>	
							<?php } ?>
							</span> 
						</span> 
					</div>
					<div class="media-body text-left" onclick="return chat('CONTACT',<?php echo $contact['Contact']['id'];?>)">
						<?php 
							$phone = preg_replace("/[^0-9]/", "", $contact['Contact']['phone_number']);
							$length = strlen($phone);
                            $number = $phone;
							/*if($length==7){
								$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
							}else if($length==8){
								$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
							}else if($length==9){
								$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
							}else if($length==10){
								$number =  preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
							}else if($length==11){
								$number = preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
							}else if($length > 11){
								$number = $phone;
							}*/
						?>
						<p class="media-heading"><span class="contactheading" id="contactheading<?php echo $contact['Contact']['id'];?>"><?php if(trim($contact['Contact']['name']) !=''){ echo ucfirst($contact['Contact']['name']); echo ' ('.$number.')';}else{ echo $number; } ?></span>
						
							<br>
							<span class="date-color" id="<?php echo $contact['Contact']['id'];?>">
								<?php if($contact['Contact']['lastmsg'] != "0000-00-00 00:00:00"){ ?>
									<?php echo date('m-d-Y h:i A',  strtotime($contact['Contact']['lastmsg'])); ?>
								<?php  }else{ ?>
									<?php echo date('m-d-Y h:i A',  strtotime($contact['Contact']['created'])); ?>
								<?php  } ?>
							</span>
						
						<span class="contactmsg" id="contactmsg<?php echo $contact['Contact']['id'];?>"></span>
						</p>
						<?php if(isset($contact['Log'][0])){ ?>
						<?php if($contact['Log'][0]['msg_type']=='text'){?>
							<p class="media-text" id="contactlatest<?php echo $contact['Contact']['id'];?>"><?php echo ucfirst(substr($contact['Log'][0]['text_message'],0,20)); ?>....</p>
						<?php }else{ ?>
							<p class="media-text" id="contactlatest<?php echo $contact['Contact']['id'];?>">New Voicemail....</p>
						<?php } ?>
						<?php } ?>
					</div>
				</li>
			<?php }else if(isset($contact['Group']['id'])){?>
				<li class="media border-dotted <?php echo $cont_color;?>" id="group_list<?php echo $contact['Group']['id'];?>">
					<div class="lft-img">
						<span class="pull-left">
							<a id="groupfavoritefun<?php echo $contact['Group']['id'];?>" href="#null" onclick="return groupfavoriteassign(<?php echo $contact['Group']['id'];?>,<?php echo $contact['Group']['favorite'];?>)">
								<?php if($contact['Group']['favorite']==1){ ?>
									<span class="star-img" id="groupfavoriteassign<?php echo $contact['Group']['id'];?>"><i class="fa fa-heart red" aria-hidden="true"></i></span>
								<?php }else if($contact['Group']['favorite']==0){ ?>
									<span class="star-img" id="groupfavoriteassign<?php echo $contact['Group']['id'];?>"><i class="fa fa-heart white" aria-hidden="true"></i></span>
								<?php } ?>
							</a>
							<span style="position:relative;" onclick="return chat('GROUP',<?php echo $contact['Group']['id'];?>)">
								<i class="fa fa-users" aria-hidden="true" style="font-size:20px;"></i>
							</span>
						</span>
					</div>
					<div class="media-body text-left" onclick="return chat('GROUP',<?php echo $contact['Group']['id'];?>)">
						<p class="media-heading">
							<span class="groupheading" id="groupheading<?php echo $contact['Group']['id'];?>"><?php echo ucfirst($contact['Group']['group_name']);?></span>
						</p>
						<?php if(isset($contact['GroupSmsBlast'][0])){?>
							<p class="media-text"><?php echo ucfirst(substr($contact['GroupSmsBlast'][0]['msg'],0,20)); ?>....</p>
						<?php } ?>
					</div>
				</li>
			<?php } ?>
			<?php  $i++;?>
		<?php } ?>
	<?php } ?>
<?php } ?>
</ul>