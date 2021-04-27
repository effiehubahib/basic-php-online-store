<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

if(!isset($_GET['month'])){
	$querySoldMonth = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE MONTH(tbl_cart.dateadded) = MONTH(NOW()) &&
		      						tbl_cart.delivery_status>2";
}else{
	$querySoldMonth = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE MONTH(tbl_cart.dateadded) = MONTH('".$_GET['month']."-1') &&
		      						tbl_cart.delivery_status>2";

}

$resultSoldMonth = mysqli_query($conn,$querySoldMonth);

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
		        <h3 class="panel-title"> Sold products in a month of: <?php if(!isset($_GET['month'])){ echo date("F Y");
		        	echo'<span class="pull-right white">
		        		<a href="month-sold-product.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo date("F Y",strtotime($_GET['month']));
		    		echo'<span class="pull-right white">
		        		<a href="month-sold-product.php?month='.date("Y-m",strtotime($_GET['month'])).'" target="_blank">Print</a>
		        	</span>';
		    	}?> 
		        	
		        </h3>
		      </div>
		      <div class="panel-body">
			      	<?php
			      		if(mysqli_num_rows($resultSoldMonth)>0){
			      	  
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

				      			while($productMonth=mysqli_fetch_array($resultSoldMonth))
				      			{
				      				$productTotal = $productMonth['price']*$productMonth['quantity'];
				      				$overallTotal += $productTotal;
				      				echo"<tr> 
				      				        <td>".date("Ymd",strtotime($productMonth['dateadded'])).'-'.$productMonth['id_cart']."</td> 
											<td>".$productMonth['product_name']."</td> 
											<td>".$productMonth['quantity']."</td> 
											<td>".number_format($productMonth['price'],2)."</td> 
											<td class='text-right'>".number_format($productTotal,2)."</td> 
										</tr>";
				      			}
				      			echo"<tr> 
										<td>Overall Amount</td> 
										<td colspan='5' class='text-right'>".number_format($overallTotal,2)."</td> 
									</tr>";
				      		?>
				      		</tbody> 
				      		</table> 
				      	</div>

			      	<?php
		      		}else{
		      			echo"No sold product!";
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