<p>Hello <?php echo $username; ?>,</p>

<p>
You just received a new incoming SMS to your account at <?php echo SITENAME ?>.
</p>

<p>

<b>Number:</b> <?php echo $from; ?><br/>
<b>Name:</b> <?php echo $name; ?><br/>
<b>Message:</b> <?php echo $body; ?><br/>

</p>
<p>
Go to <?php echo SITE_URL ?> to see all the details.
</p>
<p>
Regards and happy marketing!
</p>