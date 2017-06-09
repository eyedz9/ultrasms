<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo ucfirst($kiosks['Kiosks']['name']); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script src="<?php echo SITE_URL; ?>/js/jQvalidations/jquery.validation.functions.js"></script>
	<script src="<?php echo SITE_URL; ?>/js/jQvalidations/jquery.validate.js"></script>
	<script src="<?php echo SITE_URL; ?>/js/jquery.maskedinput.js"></script>
	<style>
		html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {
			border: 0 none;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 100%;
			font-style: inherit;
			font-weight: inherit;
			margin: 0;
			outline: 0 none;
			padding: 0;
			vertical-align: baseline;
		}
		<?php if($kiosks['Kiosks']['file'] !=''){ ?>
			body {margin:0; padding:0; background: <?php echo $kiosks['Kiosks']['background_color'];?> url("<?php echo SITE_URL;?>/img_kiosks/<?php echo $kiosks['Kiosks']['file'];?>");
			<?php if($kiosks['Kiosks']['style'] =='Tiled'){ ?>
			background-repeat: repeat;
			<?php }else if($kiosks['Kiosks']['style'] =='Stretched'){ ?>
			background-repeat: no-repeat;
			background-position: center;
			<?php }else{ ?>
			background-size: cover;
			<?php }?>
			
			}
		<?php }else{?>
		body {  background-color: <?php echo $kiosks['Kiosks']['background_color'];?>; }
		<?php }?>
		.container { max-width: 800px; margin: 0 auto; width:94%; padding:10px;}
		img { vertical-align: middle;}

		.text-center{
			text-align:<?php echo $kiosks['Kiosks']['alignment'];?>;
			font-size: <?php echo $kiosks['Kiosks']['fontsize'];?>px;
			font-family: <?php if($kiosks['Kiosks']['font']=='A'){ echo "Arial";}else if($kiosks['Kiosks']['font']=='H'){ echo "Helvetica";}else if($kiosks['Kiosks']['font']=='T'){ echo "Times New Roman";}else if($kiosks['Kiosks']['font']=='V'){ echo "Verdana";};?>;
			color: <?php echo $kiosks['Kiosks']['color'];?>;
			<?php if($kiosks['Kiosks']['styleB']==1){ ?>
				font-weight: bold;
			<?php } ?>
			<?php if($kiosks['Kiosks']['styleI']==1){ ?>
				font-style: italic;
			<?php } ?>
			<?php if($kiosks['Kiosks']['styleU']==1){ ?>
				text-decoration: underline;
			<?php } ?>
		
		}

		.img-box{ max-width:400px; width:100%; margin:20px auto 50px; text-align:center;}

		.img-box img{ width:100%;}
		h1, h2, h3{ color:#0b593f; margin:10px 0;}
		h1{ font-size:30px;}
		h3{ font-size:30px;}
		p.copyright {
			text-align:<?php echo $kiosks['Kiosks']['bottom_text_alignment'];?>;
			font-size: <?php echo $kiosks['Kiosks']['bottom_text_size'];?>px;
			font-family: <?php if($kiosks['Kiosks']['bottom_text_font']=='A'){ echo "Arial";}else if($kiosks['Kiosks']['bottom_text_font']=='H'){ echo "Helvetica";}else if($kiosks['Kiosks']['bottom_text_font']=='T'){ echo "Times New Roman";}else if($kiosks['Kiosks']['bottom_text_font']=='V'){ echo "Verdana";};?>;
			color: <?php echo $kiosks['Kiosks']['bottom_text_color'];?>;
			<?php if($kiosks['Kiosks']['bottom_text_styleB']==1){ ?>
				font-weight: bold;
			<?php } ?>
			<?php if($kiosks['Kiosks']['bottom_text_styleI']==1){ ?>
				font-style: italic;
			<?php } ?>
			<?php if($kiosks['Kiosks']['bottom_text_styleU']==1){ ?>
				text-decoration: underline;
			<?php } ?>
			}
		p{ color:#0b593f; margin:20px 0 0;}
		.textheader p{ color:<?php echo $kiosks['Kiosks']['color'];?>; margin:20px 0 0; }
		<?php if(($kiosks['Kiosks']['joinbuttons']==1) || ($kiosks['Kiosks']['punchcard']==1) || ($kiosks['Kiosks']['checkpoints']==1)){?>
			.button-green{ background:<?php echo $kiosks['Kiosks']['keypad_button_color'];?>; border:none; border-radius:0px; color:<?php echo $kiosks['Kiosks']['keypad_text_color'];?>; padding:10px 0px; display:inline-block; font-size:30px; text-decoration:none;cursor:pointer;}
		<?php } ?>
		.button-box { margin: 10px auto; text-align: center; max-width:400px; width:100%;}
		.button1{ width:100%; margin:10px 0;}
		.button2{ width:49%; margin:10px 0;}
		.button3{ width:49%; margin:10px 0;}
		

		.input1{ display:inline-block; padding:10px; width:61.3%;font-size: 30px;height: 36px;}
		.button4{ width:32.49%; margin:2px 0;}
		.button-box1{ max-width:500px;margin:10px auto;}

		.row {clear: both; margin: 20px 0;text-align: center;}
		.row label { display: inline-block; vertical-align: middle;  width: 100px;text-align:left;}
		.input2{ display:inline-block; padding:5px; width:61.3%;font-size: 20px;height: 25px;}

		.count {
		  display: inline-block;
		  margin: 3px 2px;
		}
		@media(max-width:768px){
			.img-box{ float:right;max-width: 350px;}
			.mid-box{ float:left;max-width: 350px;}
			.button-box1 .img-box{ float:none;max-width: 100%;}
		}
		@media(max-width:767px){
			.img-box{ float:right;max-width: 300px;}
			.mid-box{ float:left;max-width: 300px;}
			.button4 {width: 32.7%;}
			.input1{ width:61.3%;}
			.row label { width: 95%;text-align:left;}
			.input2{ width:95%;}
		}
		@media(max-width:639px){
			.img-box{ float:none;max-width: 100%;}
			.mid-box{ float:none;max-width: 100%;}
			.input1{ display:inline-block; padding:10px; width:58.3%;font-size: 20px;height: 24px;}
			.button4 {
				font-size: 20px;
				margin: 10px 0;
				width: 32%;
			}
		}

#flashMessage {
        font-size: 16px;
        }
       .message {
                background: #f6fff5 url("<?php echo SITE_URL; ?>/app/webroot/img/flashimportant.png") no-repeat scroll 15px 12px / 24px 24px;
                border: 1px solid #97db90;
                border-radius: 5px;
                color: #000;
                font-size: 15px;
                margin-bottom: 10px;
                padding: 13px 13px 13px 48px;
                text-decoration: none;
                text-shadow: 1px 1px 0 #fff;
             }
	</style>
	</head>
	<body>
	<div class="container">
		<div class="img-box"><?php if($kiosks['Kiosks']['business_logo'] !=''){?><img src="<?php echo SITE_URL.'/img_kiosks/'.$kiosks['Kiosks']['business_logo'];?>" alt="" title=""  /> <?php } ?></div>
		<div class="mid-box">
			<span><?php echo $this->Session->flash(); ?></span>
			<div class="button-box">
				<a class="button-green button1" href="<?php echo SITE_URL;?>/kiosks/view/<?php echo $code;?>">Home</a>
			</div>
			<p class="copyright"><?php if($kiosks['Kiosks']['bottom_text'] !=''){ echo $kiosks['Kiosks']['bottom_text']; }?></p>

		</div>
	</div>
</body>
</html>