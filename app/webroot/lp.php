<?php 

include('database.php');

   $base64 = base64_decode($_GET['id']);
     
   $changeid = str_replace('=' ,'', $base64);
   
   $id = str_replace('+' ,'', $changeid );

   $result = mysql_query("SELECT * FROM mobile_pages where id=".$id);
   
   if(!empty($result)){
   
   $row = mysql_fetch_array($result);
   
   }
 
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="viewport" content="user-scalable=no, width=device-width" />
<title><?php echo ucfirst($row['title']); ?></title>
<style>

*{ margin:0; padding:0;}

.clear{ clear:both;}

body{ background:<?php echo $row['bodybg_color']; ?>;}

.header{ background:<?php echo $row['header_color']; ?>; height:auto;margin: 0;padding: 10px;}

.logo{ float:left; color:<?php echo $row['headerfont_color']; ?>; font-family:Arial, Helvetica, sans-serif; font-size:24px; margin:0px 20px 0;}

.logo img{ float:left;width:100%;}

.logo span{ margin:10px;line-height: 44px;}

.main-container{ text-align:center;}

.text{border-radius:10px; padding:10px; color:#731025; font-family:Arial, Helvetica, sans-serif; font-size:16px; width:auto; margin:34px auto 0;font-weight: bold;}

/*.img{padding:10px; width:300px; margin:20px auto 70px;}*/
.img{padding:10px;}



.footer{  background:<?php echo $row['footer_color']; ?>; height:120px;}

@media(max-width: 1023px) {}

@media(max-width: 767px) {}

@media(max-width: 639px) {}

@media (max-width: 480px) {}

@media (max-width: 360px) {}

@media (max-width: 320px) { 

.logo{  font-size:18px; margin:0px 20px 0;}

.text{ width:90%; }

.img{ width:90%;}

.img img{ width:100%;}

.text iframe{ width:240px; height:200px;}
}


</style>
</head>

<body>


<?php if(!empty($result)){?>
<div class="header">

<div class="logo">

<?php 

$logo=$row['header_logo'];


if($logo!=''){?>

<img src="<?php echo SITEURL;?>/pages/<?php echo $row['header_logo']; ?>" alt=""/>

<?php }else{ ?>

<span><?php echo ucfirst($row['title']); ?></span>

<?php } ?>



</div>
<div class="clear"></div>

</div>
<div class="clear"></div>

<div class="main-container">

<div class="text"><?php echo $row['description']; ?></div>


<?php if($row['map_url']!=''){?>

<div class="img"><?php echo $row['map_url']; ?></div>

<?php } ?>

<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="footer">
<span style="float:left; color:<?php echo $row['footertext_color']; ?>; font-family:Arial, Helvetica, sans-serif; font-size:24px; margin:50px 20px 0;"><?php echo ucfirst($row['footertext']); ?></span>
</div>
<?php }else{ ?>
   
 <div  style="color:black;font-size:17px;padding: 30px;"><h1>Page Not Found</h1></div>
   
   <?php }
   
   ?>

</body>
</html>
