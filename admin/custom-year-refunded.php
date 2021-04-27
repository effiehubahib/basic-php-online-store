<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

if(!isset($_GET['year'])){
	$queryYearRefunded = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE YEAR(tbl_cart.dateadded) = YEAR(NOW()) &&
		      						tbl_cart_product.refunded=1";
}else{
	$queryYearRefunded = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE YEAR(tbl_cart.dateadded) = '".$_GET['year']."' &&
		      						tbl_cart_product.refunded=1";
}

$resultYearRefunded = mysqli_query($conn,$queryYearRefunded);

?>
<script>
  $( function() {
    $("#searchYear").datepicker({ 
		        dateFormat: 'yy',
		        changeYear: true,
		        changeMonth: false,
		        showButtonPanel: true,
		 
		        onClose: function(dateText, inst) {  
		            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 
		            $(this).val($.datepicker.formatDate('yy', new Date(year, 1)));
		            makeAjaxCallpieYear( $(this).val());
		        }
		});
		
		$("#searchYear").focus(function () {
		    $(".ui-datepicker-calendar").hide();
		     $(".ui-datepicker-month").hide();
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
  			<!--Year's refunded product-->
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">Select year to view</h3>
		      </div>
		      <div class="panel-body">
		      	 <div class="monthOrderSearch">
		        	<form method="GET" action="">
		        		<input type="search" class="input-sm" name="year" id="searchYear" >
		        		<input type="submit" name="search" value="Search" >
		        	</form>
		        </div>
		      </div>
		    </div>
		  

	     </div>  


	     	     <div class="col-xs-12 col-sm-12 col-md-8">
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title"> Refunded products in a year of: <?php if(!isset($_GET['year'])){ echo date("Y");
		        	echo'<span class="pull-right">
		        		<a href="yearly-refunded-product.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo $_GET['year'];
		    		echo'<span class="pull-right">
		        		<a href="yearly-refunded-product.php?year='.$_GET['year'].'" target="_blank">Print</a>
		        	</span>';
		    	}?> 
		        	
		        </h3>
		      </div>
		      <div class="panel-body">
			      	<?php
			      		if(mysqli_num_rows($resultYearRefunded)>0){
			      	  
			      	  ?>
				      	<div class="col-xs-12 col-sm-12 col-md-12">
				      		<table class="table table-bordered table-hover"> 
							<thead> 
								<tr> 
									<th>Product Name</th> 
									<th>Quantity</th> 
									<th>Unit Price</th> 
									<th>Total</th> 
								</tr> 
							</thead> 
							<tbody> 
				      		<?php 
				      			$overallTotal = 0;

				      			while($productRefunded=mysqli_fetch_array($resultYearRefunded))
				      			{
				      				$productTotal = $productRefunded['price']*$productRefunded['quantity'];
				      				$overallTotal += $productTotal;
				      				echo"<tr> 
											<td>".$productRefunded['product_name']."</td> 
											<td>".$productRefunded['quantity']."</td> 
											<td>".number_format($productRefunded['price'],2)."</td> 
											<td class='text-right'>".number_format($productTotal,2)."</td> 
										</tr>";
				      			}
				      			echo"<tr> 
										<td>Overall Amount</td> 
										<td colspan='3' class='text-right'>".number_format($overallTotal,2)."</td> 
									</tr>";
				      		?>
				      		</tbody>
				      		</table> 
				      	</div>

			      	<?php
		      		}else{
		      			echo"No refunded product!";
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