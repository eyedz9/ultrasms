<p>Hello <?php echo $firstname; ?>,</p>

<p>
Your account with <?php echo SITENAME ?> hasn't been updated from your purchase of package <?php echo $packagename ?>. Please see the status and reason below.
</p>

<p>
<b>PayPal payment status:</b> <?php echo $status; ?><br/>
<b>PayPal pending reason:</b> <?php echo $reason; ?><br/>
</p>

<p>
Once payment clears and is marked "Completed" in PayPal, your account will automatically be updated for package <?php echo $packagename ?>.
</p>

<p>
Regards and happy marketing!
</p>