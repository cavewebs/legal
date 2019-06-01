<html>
	<head>
	    <meta charset="utf-8">
	    <title>Invoice for <?php echo $user->u_name?></title>
	    
	    <style>
	    .invoice-box {
	        max-width: 800px;
	        margin: auto;
	        padding: 30px;
	        border: 1px solid #eee;
	        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
	        font-size: 16px;
	        line-height: 24px;
	        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
	        color: #555;
	    }
	    
	    .invoice-box table {
	        width: 100%;
	        line-height: inherit;
	        text-align: left;
	    }
	    
	    .invoice-box table td {
	        padding: 5px;
	        vertical-align: top;
	    }
	    
	    .invoice-box table tr td:nth-child(2) {
	        text-align: right;
	    }
	    
	    .invoice-box table tr.top table td {
	        padding-bottom: 20px;
	    }
	    
	    .invoice-box table tr.top table td.title {
	        font-size: 45px;
	        line-height: 45px;
	        color: #333;
	    }
	    
	    .invoice-box table tr.information table td {
	        padding-bottom: 40px;
	    }
	    
	    .invoice-box table tr.heading td {
	        background: #eee;
	        border-bottom: 1px solid #ddd;
	        font-weight: bold;
	    }
	    
	    .invoice-box table tr.details td {
	        padding-bottom: 20px;
	    }
	    
	    .invoice-box table tr.item td{
	        border-bottom: 1px solid #eee;
	    }
	    
	    .invoice-box table tr.item.last td {
	        border-bottom: none;
	    }
	    
	    .invoice-box table tr.total td:nth-child(2) {
	        border-top: 2px solid #eee;
	        font-weight: bold;
	    }
	    
	    @media only screen and (max-width: 600px) {
	        .invoice-box table tr.top table td {
	            width: 100%;
	            display: block;
	            text-align: center;
	        }
	        
	        .invoice-box table tr.information table td {
	            width: 100%;
	            display: block;
	            text-align: center;
	        }
	    }
	    
	    /** RTL **/
	    .rtl {
	        direction: rtl;
	        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
	    }
	    
	    .rtl table {
	        text-align: right;
	    }
	    
	    .rtl table tr td:nth-child(2) {
	        text-align: left;
	    }
	    </style>
	</head>

	<body>
	    <div class="invoice-box">
	        <table cellpadding="0" cellspacing="0">
	            <tr class="top">
	                <td colspan="2">
	                    <table>
	                        <tr>
	                            <td class="title">
	                                <img src="<?php echo base_url()?>static-content/images/logo-s2-white2x.png" style="width:100%; max-width:300px;">
	                            </td>
	                            
	                            <td>
	                                Invoice #: 00191<?php echo $invoice->s_id?><br>
	                                Created: <?php echo date('M d, Y', strtotime($invoice->s_created));?><br>
	                                Due: <?php echo date('M d, Y', strtotime($invoice->s_created));?>
	                            </td>
	                        </tr>
	                    </table>
	                </td>
	            </tr>
	            
	            <tr class="information">
	                <td colspan="2">
	                    <table>
	                        <tr>
	                            <td>
	                                Make Payment to,<br>
	                                Sterling Bank PLC, <br>
	                                Shopily Ecommerce Ventures Ltd<br>
	                                0069748667 - Current Account
	                            </td>
	                            
	                            <td>
	                                <?php echo $user->u_name ?><br>
	                                Transaction Ref: <?php echo $invoice->s_ref?><br>
	                                <?php echo $user->u_email?>
	                            </td>
	                        </tr>
	                    </table>
	                </td>
	            </tr>
	            
	            <tr class="heading">
	                <td>
	                    Payment Method
	                </td>
	                
	                <td> Amount 
	                </td>
	            </tr>
	            
	            <tr class="details">
	                <td>
	                    Bank
	                </td>
	                
	                <td>
	                    N3,500.00
	                </td>
	            </tr>
	            
	            <tr class="heading">
	                <td>
	                    Item
	                </td>
	                
	                <td>
	                    Price
	                </td>
	            </tr>
	            
	            <tr class="item">
	                <td>
	                    Seller Verification Fee
	                </td>
	                
	                <td>
	                    N5,000.00
	                </td>
	            </tr>
	            
	            <tr class="item">
	                <td>
	                    Discount (30%)
	                </td>
	                
	                <td>
	                    - N1,500.00
	                </td>
	            </tr>
	            
	            
	            
	            <tr class="total">
	                <td></td>
	                
	                <td>
	                   Total: N3,500.00
	                </td>
	            </tr>
	            <tr>
	            	<td><small>After payment send email or DM with transaction reference and receipt to @legitshopng on Instagram or payments@legistshop.com.ng</small>
	            	</td>
	            </tr>
	        </table>
	    </div>
	</body>
</html>