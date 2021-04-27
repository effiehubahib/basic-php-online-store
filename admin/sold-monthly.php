<?php

include_once("admin-config.php");
include_once("admin-header.php");

if(!isset($_GET['day'])){
  $querySoldToday = "SELECT tbl_product.product_name as product_name,
                        tbl_product.size as size,
                        tbl_product.product_id as product_id,
                        tbl_cartproduct.quantity as quantity,
                        tbl_cart.dateadded as dateadded,
                        tbl_cartproduct.price as price,
                        tbl_cartproduct.cart_id as cart_id
                      FROM tbl_cartproduct LEFT JOIN tbl_cart 
                      ON tbl_cartproduct.cart_id= tbl_cart.cart_id 
                      LEFT JOIN tbl_product
                      ON tbl_cartproduct.product_id = tbl_product.product_id

                      WHERE MONTH(tbl_cart.dateadded) = MONTH(NOW()) &&
                      tbl_cart.delivery_status='Delivered'";
}else{

  $querySoldToday = "SELECT tbl_product.product_name as product_name,
                        tbl_product.size as size,
                        tbl_product.product_id as product_id,
                        tbl_cartproduct.quantity as quantity,
                        tbl_cart.dateadded as dateadded,
                        tbl_cartproduct.price as price,
                        tbl_cartproduct.cart_id as cart_id
                      FROM tbl_cartproduct LEFT JOIN tbl_cart 
                      ON tbl_cartproduct.cart_id= tbl_cart.cart_id 
                      LEFT JOIN tbl_product
                      ON tbl_cartproduct.product_id = tbl_product.product_id

                      WHERE MONTH(tbl_cart.dateadded) = MONTH('".$_GET['day']."') &&
                      tbl_cart.delivery_status='Delivered'";
}
$resultSoldToday = mysqli_query($conn,$querySoldToday);
?>

 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Monthly Sold Product</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
        <div class="col-lg-4">
        <form action="sold-monthly.php" method="GET" id="account-creation_form" class="std box" enctype="multipart/form-data">
        <div class="required form-group">
                  <label for="username">Select Day<sup>*</sup></label>
                  <input type="date" name="day" class="form-control " value="<?php if(isset($_POST['day'])){ echo $_POST['day'];}?>">
                  <br/>
                  <button type="submit" name="addProduct" id="addProduct" class="btn btn-default button button-medium">
                  <span>Go!<i class="icon-chevron-right right"></i></span>
                </button>
                </div>
        </form>
        </div>
        </div>
        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Size</th>
                  <th>ADMIN'S ACTION</th>        
                </tr>
            </thead>
            <tbody>
             <?php 
              while($data=mysqli_fetch_assoc($resultSoldToday))
              {
                echo "<tr>";
                echo "<td>".$data['product_name']."</td>";
                echo "<td>".$data['quantity']."</td>";
                echo "<td>".$data['price']."</td>";
                echo "<td>".$data['size']."</td>";
                
                echo "<td><a class='btn btn-default' role='button' href='view-customer-order.php?id=".$data['cart_id']."'>View Cart </a> ";
                
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
<script>
  $( function() {
    $( "#sold_day" ).datepicker({dateFormat:'yy-mm-dd'});
  } );
  </script>
</div>
<?php
include_once("admin-footer.php");
?>