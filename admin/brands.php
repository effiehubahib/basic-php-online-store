<?php

include_once("admin-config.php");
include_once("admin-header.php");



	if(isset($_GET['id']) && isset($_GET['action']))
	{
		if($_GET['action']=="delete"){
      $queryBrandStat = "UPDATE tbl_brand SET brand_status=0 WHERE brand_id='".$_GET['id']."'";
    }else{
      $queryBrandStat = "UPDATE tbl_brand SET brand_status=1 WHERE brand_id='".$_GET['id']."'";
    }
		$resultBrandDelete = mysqli_query($conn,$queryBrandStat);
	}

	$queryBrands = "SELECT * FROM tbl_brand ";
	$result = mysqli_query($conn,$queryBrands);
	
?>

 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Brand Overview</small>
                </h1>
            </div>
        </div>

        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Brand ID</th>
                  <th>Brand Name</th>   
                  <th>Status</th>
                  <th>Admin Action</th>
                </tr>
            </thead>
            <tbody>
             <?php 
              while($brand=mysqli_fetch_assoc($result))
              {
                if($brand['brand_status']==0)
                  $brand_stat = 'Deleted';
                else
                  $brand_stat = 'Active';
                echo "<tr>";
                echo "<td>".$brand['brand_id']."</td>";
                echo "<td>".$brand['brandname']."</td>";
                echo "<td>".$brand_stat."</td>";
                
                echo "<td><a class='btn btn-default' role='button' href='brand-edit.php?id=".$brand['brand_id']."'>Edit</a>";
                echo " || ";
                if($brand['brand_status']==1){
                    echo"<a href='brands.php?id=".$brand['brand_id']."&action=delete'>Delete</a>";
                    }else{
                    echo"<a href='brands.php?id=".$brand['brand_id']."&action=activate'>Activate</a>";
                }
                
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