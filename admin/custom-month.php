<?php

include_once("admin_config.php");
include_once("admin_header.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

if(!isset($_GET['month'])){
	$queryMonthOrder = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE MONTH(tbl_cart.dateadded) = MONTH(NOW())";
}else{
	$queryMonthOrder = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE MONTH(tbl_cart.dateadded) = MONTH('".$_GET['month']."-1')";

}

$resultMonthOrder = mysqli_query($conn,$queryMonthOrder);

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
		        <h3 class="panel-title"> Order in a month of: <?php if(!isset($_GET['month'])){ echo date("F Y");
		        	echo'<span class="pull-right white">
		        		<a href="month-order.php" target="_blank">Print</a>
		        	</span>';

		    	}else{
		    		echo date("F Y",strtotime($_GET['month']));
		    		echo'<span class="pull-right white">
		        		<a href="month-order.php?month='.date("Y-m",strtotime($_GET['month'])).'" target="_blank">Print</a>
		        	</span>';
		    	}?> 
		        	
		        </h3>
		      </div>
		      <div class="panel-body">
			      	<?php
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