<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

if(!isset($_GET['day'])){
	$querySoldToday = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE DATE(tbl_cart.dateadded) = DATE(NOW()) &&
		      						tbl_cart.delivery_status>2";
}else{

	$querySoldToday = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE DATE(tbl_cart.dateadded) = DATE('".$_GET['day']."') &&
		      						tbl_cart.delivery_status>2";
}
$resultSoldToday = mysqli_query($conn,$querySoldToday);
?>
<script>
  $( function() {
    $( "#search" ).datepicker({changeMonth: true,changeYear: true,dateFormat:'yy-mm-dd'});
  } );
</script>
<div id="admin">
 <div class="columns-container">
    <div id="columns" class="container">
    	
  		<div class="col-xs-12 col-sm-12 col-md-4">
  			<!--Today's order-->
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">Select day to view</h3>
		      </div>
		      <div class="panel-body">
		      	 <div class="todayOrderSearch">
		        	<form method="GET" action="">
		        		<input type="search" class="input-sm" name="day" id="search" >
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
		        <h3 class="panel-title">Sold product on date: <?php if(!isset($_GET['day'])){echo date("F d, Y");
		        	echo'<span class="pull-right">
		        		<a href="today-sold-product.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo date("F d, Y",strtotime($_GET['day']));
		    		echo'<span class="pull-right">
		        		<a href="today-sold-product.php?day='.date("Y-m-d",strtotime($_GET['day'])).'" target="_blank">Print</a>
		        	</span>';

		    	}?> 
		        	
		        </h3>
		      </div>
		      <div class="panel-body">
			      	<?php
			      		if(mysqli_num_rows($resultSoldToday)>0){
			      	  
			      	  ?>
				      	<div class="col-xs-12 col-sm-12 col-md-12">
				      		<table class="table table-bordered table-hover"> 
							<thead> 
								<tr> 
									<th>Order #</th> 
									<th>Product Name</th> 
									<th>Quantity</th> 
									<th>Unit Price</th> 
									<th>Total</th> 
								</tr> 
							</thead> 
							<tbody> 
				      		<?php 
				      			$overallTotal = 0;

				      			while($productToday=mysqli_fetch_array($resultSoldToday))
				      			{
				      				$productTotal = $productToday['price']*$productToday['quantity'];
				      				$overallTotal += $productTotal;
				      				echo"<tr> 
				      						<td>".date("Ymd",strtotime($productToday['dateadded'])).'-'.$productToday['id_cart']."</td> 
											<td>".$productToday['product_name']."</td> 
											<td>".$productToday['quantity']."</td> 
											<td>".number_format($productToday['price'],2)."</td> 
											<td class='text-right'>".number_format($productTotal,2)."</td> 
										</tr>";
				      			}
				      			echo"<tr> 
										<td>Overall Amount</td> 
										<td colspan='4' class='text-right'>".number_format($overallTotal,2)."</td> 
									</tr>";
				      		?>
				      		</tbody> 
				      		</table> 
				      	</div>

			      	<?php
		      		}else{
		      			echo"No sold product !";
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