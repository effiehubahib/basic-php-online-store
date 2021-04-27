<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");
/*todays order or by day*/
if(isset($GET['today_order']))
{
	$queryToday = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE DATE(tbl_cart.dateadded) = DATE(".$GET['today_order'].")";
}else{

	$queryToday = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE DATE(tbl_cart.dateadded) = DATE(NOW())";
}
?>

<div id="admin_dashboard ">
 <div class="columns-container" id="admin_dashboard">
    <div id="columns" class="container">
    	<div class=" row fluid-container">
    	<h5 class="pull-right"><a href="custom-search.php" style="text-decoration:underline;">Go to Customized Search Result</a></h5>
    	</div>
  		<div class="col-xs-12 col-sm-12 col-md-4">
  			<!--Today's order-->
		    <div class="panel panel-primary">
		      <div class="panel-heading">

		        <i class="fa fa-search-plus i-today-order" aria-hidden="true"></i>
		        	<h3 class="panel-title" id="today_order">
		        	 	<?php if(isset($GET['today_order'])){?>
		        	 	<?php }else{ ?>

		        			Today's Order (<?php echo date("M d, Y");?>)
		        		<?php }?>
		        	</h3>
		        	<span class="pull-right"><a href="today-orders.php" target="_blank">Print</a></span>
		        <div class="todayOrderSearch">
		        	<form method="GET">
		        		<input type="search" class="input-sm" name="searchTodayOrder" id="searchTodayOrder">
		        		<input type="submit" name="btnTodayOrder" value="Search" >
		        	</form>
		        </div>
		      </div>
		      <div class="panel-body">
		      	<?php
		      		
					$resultToday = mysqli_query($conn,$queryToday);
					if(mysqli_num_rows($resultToday)>0){
			      	  
			      	  ?>
				      	<div class="col-xs-12 col-sm-8 col-md-12">
				      		<ul>
				      		<?php 
				      			$ctr = 0;
				      			while($cartToday=mysqli_fetch_array($resultToday))
				      			{
				      				$ctr++;
				      			echo"<li>".$ctr.". <a href='view-customer-order.php?id=".$cartToday['id_cart']."'> ".$cartToday['lastname'].", ".$cartToday['firstname']." - ".$deliverystatus[$cartToday['delivery_status']]." (".date("M d, Y h:i a",strtotime($cartToday['dateadded'])).")</a></li>";
				      			}
				      		?>
				      		</ul>
				      	</div>

			      	<?php
		      		}else{
		      			echo"No orders today!";
		      		}
		      	?>
		      </div>
		    </div>

		    <!--This week order-->
		    <div class="panel panel-primary">
		      <div class="panel-heading">

		        <i class="fa fa-search-plus i-week-order" aria-hidden="true"></i>
		        	<h3 class="panel-title" id="today_order">
		        	 	<?php if(isset($GET['week_order'])){?>

		        	 	<?php }else{ ?>

		        			This Week Orders (<?php echo date("M d, Y");?>)
		        		<?php }?>
		        	</h3>
		        	<span class="pull-right"><a href="week-order.php" target="_blank">Print</a></span>
		        <div class="weekOrderSearch">
		        	<form method="GET">
		        		<input type="search" class="input-sm" name="searchWeekOrder" id="searchWeekOrder">
		        		<input type="submit" name="btnWeekOrder" value="Search" >
		        	</form>
		        </div>
		      </div>
		      <div class="panel-body">
		      	<?php
		      		$queryWeekOrder = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE tbl_cart.dateadded > DATE_SUB(NOW(), INTERVAL 1 WEEK)";

					$resultWeekOrder = mysqli_query($conn,$queryWeekOrder);
					if(mysqli_num_rows($resultWeekOrder)>0){
			      	  
			      	  ?>
				      	<div class="col-xs-12 col-sm-8 col-md-12">
				      		<ul>
				      		<?php 
				      			$ctr = 0;
				      			while($cartWeek=mysqli_fetch_array($resultWeekOrder))
				      			{
				      				$ctr++;
				      			echo"<li>".$ctr.". <a href='view-customer-order.php?id=".$cartWeek['id_cart']."'> ".$cartWeek['lastname'].", ".$cartWeek['firstname']." - ".$deliverystatus[$cartWeek['delivery_status']]." (".date("M d, Y h:i a",strtotime($cartWeek['dateadded'])).")</a></li>";
				      			}
				      		?>
				      		</ul>
				      	</div>

			      	<?php
		      		}else{
		      			echo"No orders this week!";
		      		}
		      	?>
		      </div>
		    </div>
		    <!-- This month order-->
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		       
		     
		      <i class="fa fa-search-plus i-month-order" aria-hidden="true"></i>
		        	<h3 class="panel-title" id="month_order">
		        	 	<?php if(isset($GET['week_order'])){?>

		        	 	<?php }else{ ?>

		        			This Month Orders (<?php echo date("M , Y");?>)
		        		<?php }?>
		        	</h3>
		        	<span class="pull-right"><a href="month-order.php" target="_blank">Print</a></span>
		        <div class="monthOrderSearch">
		        	<form method="GET">
		        		<input type="search" class="input-sm" name="searchWeekOrder" id="searchWeekOrder">
		        		<input type="submit" name="btnWeekOrder" value="Search" >
		        	</form>
		        </div>
		       </div>
		      <div class="panel-body">
		      	<?php
		      		$queryMonthOrder = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE MONTH(tbl_cart.dateadded) = MONTH(NOW())";
		      		
					$resultMonthOrder = mysqli_query($conn,$queryMonthOrder);
					if(mysqli_num_rows($resultMonthOrder)>0){
			      	  
			      	  ?>
				      	<div class="col-xs-12 col-sm-8 col-md-12">
				      		<ul>
				      		<?php 
				      			$ctr = 0;
				      			while($cartMonth=mysqli_fetch_array($resultMonthOrder))
				      			{
				      				$ctr++;
				      			echo"<li>".$ctr.". <a href='view-customer-order.php?id=".$cartMonth['id_cart']."'> ".$cartMonth['lastname'].", ".$cartMonth['firstname']." - ".$deliverystatus[$cartMonth['delivery_status']]." (".date("M d, Y h:i a",strtotime($cartMonth['dateadded'])).")</a></li>";
				      			}
				      		?>
				      		</ul>
				      	</div>

			      	<?php
		      		}else{
		      			echo"No orders this month!";
		      		}
		      	?>
		      </div>
		    </div>

	     </div> 


	     <!-- Monthly Products sold -->
	     <div class="col-xs-12 col-sm-12 col-md-8">
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">This Month Sold Product<span class="pull-right"><a href="month-sold-product.php" target="_blank">Print</a></span></h3>
		      </div>
		      <div class="panel-body">
		      	<?php
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
		      		//echo $querySoldToday;exit;
					$resultSoldMonth = mysqli_query($conn,$querySoldMonth);
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
		      			echo"No sold product today!";
		      		}
		      	?>
		      </div>
		    </div>

		    <div class="col-xs-12 col-sm-12 col-md-12">
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">Today's Sold Product<span class="pull-right"><a href="today-sold-product.php" target="_blank">Print</a></span></h3>
		      </div>
		      <div class="panel-body">
		      	<?php
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
		      		//echo $querySoldToday;exit;
					$resultSoldToday = mysqli_query($conn,$querySoldToday);
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
		      			echo"No sold product today!";
		      		}
		      	?>
		      </div>
		    </div>
		    <!--Daily Refund-->
		    
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">Today Refunded Products<span class="pull-right"><a href="today-refunded-product.php" target="_blank">Print</a></span></h3>
		      </div>
		      <div class="panel-body">
		      	<?php
		      		$queryRefundedToday = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart_product.price as price
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE tbl_cart.dateadded > DATE_SUB(NOW(), INTERVAL 1 DAY) &&
		      						tbl_cart.delivery_status=8";
		      		//echo $querySoldToday;exit;
					$resultRefundedToday = mysqli_query($conn,$queryRefundedToday);
					if(mysqli_num_rows($resultRefundedToday)>0){
			      	  
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

				      			while($productToday=mysqli_fetch_array($resultRefundedToday))
				      			{
				      				$productTotal = $productToday['price']*$productToday['quantity'];
				      				$overallTotal += $productTotal;
				      				echo"<tr> 
											<td>".$productToday['product_name']."</td> 
											<td>".$productToday['quantity']."</td> 
											<td>".number_format($productToday['price'],2)."</td> 
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
		      			echo"No refunded product today!";
		      		}
		      	?>
		      </div>
		    </div>
		    <!--MOnthly Refund-->
		    
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">Monthly Refunded Products<span class="pull-right"><a href="monthly-refunded-product.php" target="_blank">Print</a></span></h3>
		      </div>
		      <div class="panel-body">
		      	<?php
		      		$querySoldToday = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart_product.price as price
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE tbl_cart.dateadded > DATE_SUB(NOW(), INTERVAL 1 MONTH) &&
		      						tbl_cart.delivery_status=8";
		      		//echo $querySoldToday;exit;
					$resultSoldToday = mysqli_query($conn,$querySoldToday);
					if(mysqli_num_rows($resultSoldToday)>0){
			      	  
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

				      			while($productToday=mysqli_fetch_array($resultSoldToday))
				      			{
				      				$productTotal = $productToday['price']*$productToday['quantity'];
				      				$overallTotal += $productTotal;
				      				echo"<tr> 
											<td>".$productToday['product_name']."</td> 
											<td>".$productToday['quantity']."</td> 
											<td>".number_format($productToday['price'],2)."</td> 
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
		      			echo"No sold product today!";
		      		}
		      	?>
		      </div>
		    </div>
	     </div> 

    </div>
 </div>
</div>
<script>


</script>