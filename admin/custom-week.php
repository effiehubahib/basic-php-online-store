<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Order closed");
date_default_timezone_set(date_default_timezone_get());

if(!isset($_GET['week'])){
	$queryToday = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE WEEK(tbl_cart.dateadded) = WEEK(NOW())";
	$resultToday = mysqli_query($conn,$queryToday);
}else{

	$queryToday = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE WEEK(tbl_cart.dateadded) = WEEK('".$_GET['week']."')";
	$resultToday = mysqli_query($conn,$queryToday);
}
?>
<script>
  $( function() {
    $( "#search" ).datepicker({changeMonth: true,showWeek: true, changeYear: true,dateFormat:'yy-mm-dd'});
  } );
</script>
<div id="admin">
 <div class="columns-container">
    <div id="columns" class="container">
    	
  		<div class="col-xs-12 col-sm-12 col-md-4">
  			<!--Today's order-->
		    <div class="panel panel-primary">
		      <div class="panel-heading">
		        <h3 class="panel-title">Select day within a week to view</h3>
		      </div>
		      <div class="panel-body">
		      	 <div class="todayOrderSearch">
		        	<form method="GET" action="">
		        		<input type="search" class="input-sm" name="week" id="search" >
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
		        <h3 class="panel-title">Order in <?php if(!isset($_GET['week'])){echo "Week #:".date("W")." of ".date("Y");
		        	echo'<span class="pull-right">
		        		<a href="today-orders.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo "Week #:".date("W",strtotime($_GET['week']))." of ".date("Y",strtotime($_GET['week']));
		    		echo'<span class="pull-right">
		        		<a href="week-order.php?week='.date("Y-m-d",strtotime($_GET['week'])).'" target="_blank">Print</a>
		        	</span>';

		    	}?> 
		        	
		        </h3>
		      </div>
		      <div class="panel-body">
			      	<?php
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
							echo"No order!";
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