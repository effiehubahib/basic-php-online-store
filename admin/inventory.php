<?php

include_once("admin-config.php");
include_once("admin-header.php");
if(!isset($_POST['datefrom']) || !isset($_POST['dateto']))
{
   $_POST['datefrom'] = date("Y-m-d");
   $_POST['dateto'] = date("Y-m-d");
 
}


 $query = "SELECT product_id,
                  (tp.quantity * tp.price) as totalamountleft,
                  product_name, suppliername, brandname, size, product_type, made, tp.price as price, tp.quantity as quantityleft
            FROM tbl_product tp LEFT JOIN tbl_brand tb ON tp.brand_id=tb.brand_id
            LEFT JOIN tbl_supplier ON tp.supplier_id=tbl_supplier.supplier_id";

	$result = mysqli_query($conn,$query);
	
?>

 <div id="page-wrapper">

    <div class="container-fluid">
        <div class="row" >
          <div id="search_column" class="center_column col-xs-10 col-sm-10">
            <form action="" method="post" id="account-creation_form" class="std box">
              
              <div class="account_creation">
                <h4 class="page-subheading">Select Date</h4>
                <div class="col-xs-10 col-md-4">
                  <label for="datefrom">Date From <sup>*</sup></label>
                  <input type="text" name="datefrom" class="form-control startDatePicker" required="true" value="<?php if(isset($_POST['datefrom'])){ echo $_POST['datefrom'];}?>">
                </div>
                <div class="col-xs-10 col-md-4">
                  <label for="dateto">Date To <sup>*</sup></label>
                  <input type="text" name="dateto" class="form-control endDatePicker" required="true" value="<?php if(isset($_POST['dateto'])){ echo $_POST['dateto'];}?>">
                </div>
              <div class="col-xs-10 col-md-4 displayblock"> 
                <label for="view">&nbsp;</label>
                <button type="submit" name="viewInventory" id="viewInventory" class="btn btn-default button button-medium">
                  <span>View Inventory<i class="icon-chevron-right right"></i></span>
                </button>
              </div>
              <br/>
              <br/>
            </form>
          </div>
        </div>
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    <small>Product Inventory:<?php echo date("M d, Y", strtotime($_POST['datefrom']))." - ".date("M d, Y", strtotime($_POST['dateto']));?></small>
                </h3>
            </div>
        </div>
        <!-- /.row -->

        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Product Name</th>
                  <th>Supplier Name</th>
                  <th>Product Brand</th>
                  <th>Product Size</th>
                  <th>Product Type</th>
                  <th>Product Made</th>
                  <th>Product Price</th> 
                  <th>Quantity Left</th>     
                  <th>Total Amount Left</th>   
                  <th>Total Amount Sold</th>
                </tr>
            </thead>
            <tbody>
             <?php 
              while($data=mysqli_fetch_assoc($result))
              {
                $querySold = "SELECT SUM(cp.quantity * cp.price) as soldtotalamount
                              FROM tbl_cartproduct cp 
                              LEFT JOIN tbl_cart c ON c.cart_id = cp.cart_id WHERE c.dateadded BETWEEN '".$_POST['datefrom']."' AND '".$_POST['dateto']."' AND product_id = ".$data['product_id']." GROUP BY cp.product_id";
                
                $resultSold = mysqli_query($conn, $querySold);
              if(mysqli_num_rows($resultSold)>0)
              {

                while($soldrow=mysqli_fetch_assoc($resultSold))
                {
                  $totalsold = $soldrow['soldtotalamount'];
                }
              }else{
                $totalsold = 0;
              }
                echo "<tr>";
                echo "<td>".$data['product_name']."</td>";
                echo "<td>".$data['suppliername']."</td>";
                echo "<td>".$data['brandname']."</td>";
                echo "<td>".$data['size']."</td>";
                echo "<td>".$data['product_type']."</td>";
                echo "<td>".$data['made']."</td>";
                echo "<td>".$data['price']."</td>";
                echo "<td>".$data['quantityleft']."</td>";
                echo "<td>".$data['totalamountleft']."</td>";
                echo "<td>".$totalsold."</td>";
                
               echo "</tr>";
              }
             ?> 
             </tbody>
              <tfoot>
                <tr style="background-color: gray;">
                 
                </tr>
            </tfoot>
        </table>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>


<?php
include_once("admin-footer.php");
?>
<script src="../js/jquery-1.12.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script>
jQuery( document ).ready(function() {
  jQuery( function() {
    $(".startDatePicker").datepicker({ 
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      maxDate:  new Date(),
      onSelect: function(date){

          var selectedDate = new Date(date);
          var msecsInADay = 86400000;
          var endDate = new Date(selectedDate.getTime() + msecsInADay);

          $("endDatePicker").datepicker( "option", "minDate", endDate );
          $("endDatePicker").datepicker( "option", "maxDate", '+2y' );
      }
  });

  $(".endDatePicker").datepicker({ 
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      maxDate:  new Date(),
  });
  });

  

});
</script>