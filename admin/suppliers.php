<?php

include_once("admin-config.php");
include_once("admin-header.php");

		
	if(isset($_GET['supplier_id']))
	{
		$querySupplierDelete = "DELETE FROM tbl_supplier WHERE supplier_id='".$_GET['supplier_id']."'";
		$resultDeleteSupplier = mysqli_query($conn,$querySupplierDelete);
	}
	$querySuppliers = "SELECT * FROM tbl_supplier ORDER BY supplier_id DESC";
  
	$resultSuppliers = mysqli_query($conn,$querySuppliers);
	
?>

 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Suppliers Overview</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->
        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Supplier ID</th>
                  <th>Supplier Name</th>
                  <th>Contact Name</th>
                  <th>Contact Number</th>
                  <th>Address</th>
                  <th>ADMIN'S ACTION</th>        
                </tr>
            </thead>
            <tbody>
             <?php 
              while($data=mysqli_fetch_assoc($resultSuppliers))
              {
                echo "<tr>";
                echo "<td>".$data['supplier_id']."</td>";
                echo "<td>".$data['suppliername']."</td>";
                echo "<td>".$data['contactname']."</td>";
                echo "<td>".$data['contactnumber']."</td>";
                echo "<td>".$data['address']."</td>";
                echo "<td><a class='btn btn-default' role='button' href='supplier-edit.php?id=".$data['supplier_id']."'>Edit </a> || <a class='btn btn-default' role='button' href='suppliers.php?supplier_id=".$data['supplier_id']."'>Delete</a>";
                
               echo "</tr>";
              }
             ?> 
             </tbody>
              <tfoot>
                <tr style="background-color: gray;">
                 
                </tr>
            </tfoot>
        </table>



    </div>
    <!-- /.container-fluid -->

</div>
<?php
include_once("admin-footer.php");
?>