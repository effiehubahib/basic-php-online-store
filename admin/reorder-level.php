<?php

include_once("admin-config.php");
include_once("admin-header.php");

		
	
	$query = "SELECT * FROM tbl_product LEFT JOIN tbl_brand ON tbl_product.brand_id=tbl_brand.brand_id
            LEFT JOIN tbl_supplier ON tbl_product.supplier_id = tbl_supplier.supplier_id
            WHERE quantity<=reorder_level";
	$result = mysqli_query($conn,$query);
	
?>

 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Reorder List</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Product Name</th>
                  <th>Supplier Name</th>
                  <th>Brand</th>
                  <th>Size</th>
                  <th>Type</th>
                  <th>Made</th>
                  <th>Price</th>    
                  <th>Quantity</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
             <?php 
              while($data=mysqli_fetch_assoc($result))
              {
                echo "<tr>";
                echo "<td>".$data['product_name']."</td>";
                echo "<td><a href='supplier-edit.php?id=".$data['supplier_id']."'>".$data['suppliername']."</a></td>";
                echo "<td>".$data['brandname']."</td>";
                echo "<td>".$data['size']."</td>";
                echo "<td>".$data['product_type']."</td>";
                echo "<td>".$data['made']."</td>";
                echo "<td>".$data['price']."</td>";
                echo "<td>".$data['quantity']."</td>";
                echo "<td><a href='add-purchase-order.php?supplier_id=".$data['supplier_id']."'>Purchase Order</a></td>";
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