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

	<h1>Past Receipts</h1>
<div class="loginbox">
	<div class="loginner">
	<?php if(!empty($invoicedetils)){?>
      <?php echo $this->Html->link("View All", array('controller' => 'users', 'action' => 'viewallreceipt'), array('class' => 'inputbutton','style'=>'float: right;'))?>
	  
	  <br/><br/><br/>
		<table id="tableOne" cellspacing="0" cellpadding="0"style="width:100%;" >
		<thead>
		<tr>
		
			<th class="tc">Amount</th>
			<th class="tc">Profile/Txn ID</th>
			<th class="tc">Type</th>
			<th class="tc">Receipt Date</th>
			
			
		</tr>
				
	    </thead>
				
		<?php 
		
		$i = 0;
		foreach($invoicedetils as $invoicedetil) { 
		$class = null;
		
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
		?>
	 <tbody>		
	<tr class="first">
	<tr <?php echo $class;?>> 
			 
			   <td class="tc">
			   
			   
			   <?php 
	       $currencycode=PAYMENT_CURRENCY_CODE;
			
	      if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
              
              <?php echo '$'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='JPY'){ ?>
              <?php echo '¥'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='BRL'){ ?>
              <?php echo 'R$'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='PHP'){ ?>
              <?php echo '₱'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='ILS'){ ?>
              <?php echo '₪'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='EUR'){ ?>
              <?php echo '€'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='GBP'){ ?>
              <?php echo '£'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
              <?php echo 'kr'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php } else if($currencycode=='CHF'){ ?>
              <?php echo 'CHF'.$invoicedetil['Invoice']['amount'] ?></td>
              <?php }?>
			   			   
			   <td class="tc"><?php echo $invoicedetil['Invoice']['txnid'] ?></td>
			   <td class="tc"><?php 
			   
			   if($invoicedetil['Invoice']['type']==0){
			   
			   echo "PayPal";
			   
			   }else{
			   echo "2Checkout";
			   }
			   ?></td>
			  <td class="tc"><?php echo $invoicedetil['Invoice']['created'] ?></td>
			 
			  </tbody>
			  <?php } ?>
</table>
<?php }else{ ?>

<p>No receipts found.</p>
<?php } ?>
	</div>
</div>
	
		
		