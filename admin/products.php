<?php

include_once("admin-config.php");
include_once("admin-header.php");

		
	if(isset($_GET['product_id']))
	{
		$queryProdDelete = "DELETE FROM tbl_product WHERE product_id='".htmlentities($_GET['product_id'])."'";
		$resultProduct = mysqli_query($conn,$queryProdDelete);
	}
	$query = "SELECT * FROM tbl_product LEFT JOIN tbl_brand ON tbl_product.brand_id=tbl_brand.brand_id
            LEFT JOIN tbl_supplier ON tbl_product.supplier_id=tbl_supplier.supplier_id ";
	$result = mysqli_query($conn,$query);
	
?>

 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Product Overview</small>
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
                  <th>ADMIN'S ACTION</th>        
                </tr>
            </thead>
            <tbody>
             <?php 
              while($data=mysqli_fetch_assoc($result))
              {
                echo "<tr>";
                echo "<td>".$data['product_name']."</td>";
                echo "<td>".$data['suppliername']."</td>";
                echo "<td>".$data['brandname']."</td>";
                echo "<td>".$data['size']."</td>";
                echo "<td>".$data['product_type']."</td>";
                echo "<td>".$data['made']."</td>";
                echo "<td>".$data['price']."</td>";
                echo "<td>".$data['quantity']."</td>";
                echo "<td><a class='btn btn-default' role='button' href='product-edit.php?id=".$data['product_id']."'>Edit </a> || <a class='btn btn-default' role='button' href='products.php?product_id=".$data['product_id']."'>Delete</a>";
                
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