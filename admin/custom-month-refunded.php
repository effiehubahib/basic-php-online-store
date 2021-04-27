<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

if(!isset($_GET['month'])){
	$queryRefundedMonth = "SELECT tbl_product.product_name as product_name,
										tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart,
		      							tbl_cart_product.refund_amount as refund_amount
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE MONTH(tbl_cart.dateadded) = MONTH(NOW())  &&
		      						tbl_cart_product.refunded=1";
}else{
	$queryRefundedMonth = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart,
		      							tbl_cart_product.refund_amount as refund_amount
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE MONTH(tbl_cart.dateadded) = MONTH('".$_GET['month']."-1') &&
		      						tbl_cart_product.refunded=1";


}

$resultRefundedMonth = mysqli_query($conn,$queryRefundedMonth);

?>
<script>
  $( function() {
    $("#monthSearch").datepicker({ 
        dateFormat: 'mm-yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
 
        onClose: function(dateText, inst) {  
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val(); 
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 
            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
        }
    });

    $("#monthSearch").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });    
     
	});
  } );
</script>
<div id="admin">
 <div class="columns-container">
    <div id="columns" class="container">
    	
  		<div class="col-xs-12 col-sm-12 col-md-4">
  			<!--Today's order-->
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">Select month to view</h3>
		      </div>
		      <div class="panel-body">
		      	 <div class="monthOrderSearch">
		        	<form method="GET" action="">
		        		<input type="search" class="input-sm" name="month" id="monthSearch" >
		        		<input type="submit" name="search" value="Search" >
		        	</form>
		        </div>
		      </div>
		    </div>
		  

	     </div>  


	     <!-- Monthly Products sold -->
	     <div class="col-xs-12 col-sm-12 col-md-8">
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title"> Refunded products in a month of: <?php if(!isset($_GET['month'])){ echo date("F Y");
		        	echo'<span class="pull-right white">
		        		<a href="monthly-refunded-product.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo date("F Y",strtotime($_GET['month']));
		    		echo'<span class="pull-right white">
		        		<a href="monthly-refunded-product.php?month='.date("Y-m",strtotime($_GET['month'])).'" target="_blank">Print</a>
		        	</span>';
		    	}?> 
		        	
		        </h3>
		      </div>
		      <div class="panel-body">
			      	<?php
			      		if(mysqli_num_rows($resultRefundedMonth)>0){
			      	  
			      	  ?>
				      	<div class="col-xs-12 col-sm-12 col-md-12">
				      		<table class="table table-bordered table-hover"> 
							<thead> 
								<tr> 
									<th>Order #</th> 
									<th>Product Name</th> 
									<th>Quantity</th> 
									<th>Unit Price</th>
									<th>Deduction</th> 
									<th>Total</th> 
								</tr> 
							</thead> 
							<tbody> 
				      		<?php 
				      			$overallTotal = 0;
				      			$totalDeduction = 0;
				      			while($productRefund=mysqli_fetch_array($resultRefundedMonth))
				      			{
				      				$productTotal = $productRefund['price']*$productRefund['quantity'];
				      				$overallTotal += $productTotal;
				      				$totalDeduction += $productRefund['refund_amount'];
				      				echo"<tr> 
				      						<td>".date("Ymd",strtotime($productRefund['dateadded'])).'-'.$productRefund['id_cart']."</td> 
											<td>".$productRefund['product_name']."</td> 
											<td>".$productRefund['quantity']."</td> 
											<td>".number_format($productRefund['price'],2)."</td> 
											<td>".number_format($productRefund['refund_amount'],2)."</td> 
											<td class='text-right'>".number_format($productTotal,2)."</td> 

										</tr>";
				      			}
				      			echo"<tr> 
										<td>Overall Amount</td> 
										<td colspan='3'></td> 
										<td class='text-right'>".number_format($totalDeduction,2)."</td>
										<td class='text-right'>".number_format($overallTotal,2)."</td> 
									</tr>";
				      		?>
				      		</tbody> 
				      		</table> 
				      	</div>

			      	<?php
		      		}else{
		      			echo"No refunded product !";
		      		}
			      	?>
		      </div>
		    </div>

		    <div class="col-xs-12 col-sm-12 col-md-12">
		    
		  
		    </div>
	     </div> 

    </div>
 </div>
</div>