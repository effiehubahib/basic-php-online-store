<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

if(!isset($_GET['year'])){
	$queryYearOrder = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE YEAR(tbl_cart.dateadded) = YEAR(NOW())";
}else{
	$queryYearOrder = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE YEAR(tbl_cart.dateadded) ='".$_GET['year']."'";

}

$resultYearOrder = mysqli_query($conn,$queryYearOrder);

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
  			<!--Today's order-->
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
		        <h3 class="panel-title"> Order in a year of: <?php if(!isset($_GET['year'])){ echo date("Y");
		        	echo'<span class="pull-right">
		        		<a href="year-order.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo $_GET['year'];
		    		echo'<span class="pull-right">
		        		<a href="year-order.php?year='.$_GET['year'].'" target="_blank">Print</a>
		        	</span>';
		    	}?> 
		        	
		        </h3>
		      </div>
		      <div class="panel-body">
			      	<?php
			      		if(mysqli_num_rows($resultYearOrder)>0){
			      	  
			      	  ?>
				      	<div class="col-xs-12 col-sm-8 col-md-12">
				      		<ul>
				      		<?php 
				      			$ctr = 0;
				      			while($cartYear=mysqli_fetch_array($resultYearOrder))
				      			{
				      				$ctr++;
				      			echo"<li>".$ctr.". <a href='view-customer-order.php?id=".$cartYear['id_cart']."'> ".$cartYear['lastname'].", ".$cartYear['firstname']." - ".$deliverystatus[$cartYear['delivery_status']]." (".date("M d, Y h:i a",strtotime($cartYear['dateadded'])).")</a></li>";
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