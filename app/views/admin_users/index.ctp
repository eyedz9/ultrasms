<div class="adminUsers index" >
<h2><?php __('Menu');?></h2>

<table cellpadding="0" cellspacing="0" style="width:270px;background: none repeat scroll 0 0 #fbfbfb;border: 1px solid #f1f1f1;border-radius: 0;box-shadow: 0 0 rgba(0, 0, 0, 0.1), 0 0 0 1px #ffffff inset; margin-bottom: 20px; padding: 10px;">
<tr >
	<td  style="text-align:left; padding-left:10px;border-top: 1px solid #ededed;">
	<?php echo $html->link('Main Config','/admin/configs/index')?>
	</td>
</tr>

<!--<tr class="menualtrow">
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('PayPal Config','/admin/paypals/index')?>
	</td>
</tr>-->

<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('DB Config','/admin/paypals/dbconfig')?>
	</td>
</tr>


<tr class="menualtrow">
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Add-On Packages','/admin/packages/index')?>
	</td>
</tr>

<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Add Add-On Packages','/admin/packages/add')?>
	</td>
</tr>

<tr class="menualtrow">
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Monthly Packages','/admin/packages/monthlypackage')?>
	</td>
</tr>

<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Add Monthly Packages','/admin/packages/addmonthlypackage')?>
	</td>
</tr>

<tr class="menualtrow">
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Monthly Number Packages','/admin/packages/monthlynumberpackage')?>
	</td>
</tr>

<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Add Monthly Number Packages','/admin/packages/addmonthlynumberpackage')?>
	</td>
</tr>

<tr class="menualtrow">
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Users','/admin/users/index')?>
	</td>
</tr>

<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Referrals','/admin/referrals/index')?>
	</td>
</tr>

<tr class="menualtrow">
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Reports','/admin/users/user_messages?all=show')?>
	</td>
</tr>

<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Change Password','/admin_users/change_password')?>
	</td>
</tr>

</table>
</div>
