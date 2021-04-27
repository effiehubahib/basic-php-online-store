<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

if(!isset($_GET['day'])){
	$queryToday = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE DATE(tbl_cart.dateadded) = DATE(NOW())";
	$resultToday = mysqli_query($conn,$queryToday);
}else{

	$queryToday = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE DATE(tbl_cart.dateadded) = DATE('".$_GET['day']."')";
	$resultToday = mysqli_query($conn,$queryToday);
}
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
		        <h3 class="panel-title">Daily Order: <?php if(!isset($_GET['day'])){echo date("F d, Y");
		        	echo'<span class="pull-right white">
		        		<a href="today-orders.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo date("F d, Y",strtotime($_GET['day']));
		    		echo'<span class="pull-right white">
		        		<a href="today-orders.php?day='.date("Y-m-d",strtotime($_GET['day'])).'" target="_blank">Print</a>
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