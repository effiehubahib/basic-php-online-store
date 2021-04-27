<?php

include_once("admin-config.php");
include_once("admin-header.php");

		
	if(isset($_GET['size_id']))
	{
		$querySizeDelete = "DELETE FROM tbl_size WHERE size_id='".htmlentities($_GET['size_id'])."'";
		$resultSize = mysqli_query($conn,$querySizeDelete);
        echo "<script>alert('Size successfully deleted');</script>";
	}
    
	$query = "SELECT * FROM tbl_size ";
	$result = mysqli_query($conn,$query);
	
?>

 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Size Overview</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Size ID</th>
                  <th>Sizename</th>
                  <th>Description</th>
                  <th>ADMIN'S ACTION</th>        
                </tr>
            </thead>
            <tbody>
            
              <?php 
              while($data=mysqli_fetch_assoc($result))
              {
                echo "<tr>";
                echo "<td>".$data['size_id']."</td>";
                echo "<td>".$data['sizename']."</td>";
                echo "<td>".$data['description']."</td>";
                echo "<td><a class='btn btn-default' role='button' href='size-edit.php?size_id=".$data['size_id']."'>Edit</a> || <a class='btn btn-default' role='button' href='sizes.php?size_id=".$data['size_id']."'>Delete</a>";
                
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