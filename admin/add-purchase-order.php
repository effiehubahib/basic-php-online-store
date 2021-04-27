<?php

include_once("admin-config.php");
include_once("admin-header.php");

$setId = '';
$error='';
	if(isset($_GET['supplier_id'])){
		$setId = $_GET['supplier_id'];
	}else{
		echo "<script>alert('No supplier found');window.location='reorder-level.php'</script>";
	}

	if(isset($_POST['generatePO'])){
		$supplier_id = $_POST['supplier_id'];
		$datecreated = date("Y-m-d H:i:s");
		$addquantity = $_POST['addquantity'];

			$addStockquery = "INSERT INTO tbl_purchase_order(supplier_id, datecreated, status) 
					VALUES('$supplier_id','$datecreated','Pending')";

			$resultQuery = mysqli_query($conn,$addStockquery);

			if ($resultQuery)
			{  
	            //get P.O. ID created
	             $query = "SELECT LAST_INSERT_ID()";
	             $result = mysqli_query($conn,$query);
	              while($row_id=mysqli_fetch_array($result)){
	                $po_id = $row_id['0'];
			      }
			     
				if(sizeof($addquantity)>0)
				{
					foreach($addquantity as $prod_id => $value){
						$addPODetailsQuery = "INSERT INTO tbl_purchase_order_details(product_id, quantity,po_id) 
							VALUES('$prod_id','$value','$po_id')";
						$resultPODetailsQuery = mysqli_query($conn,$addPODetailsQuery);
					}
				}
				echo "<script>alert('Product successfully ordered');window.location='supplier-purchase-order.php'</script>";
			}
			else
			{
				$error="Error Saving!";
			}
	}

	$querySuppliers = "SELECT * FROM tbl_supplier WHERE supplier_id = '".$_GET['supplier_id']."'";
	$resultSuppliers = mysqli_query($conn,$querySuppliers);	
?>

<div id="product-add">
 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Add New Purchase Order</small>
                </h1>
            </div>
        </div>
		<div class="columns-container">
					<div>
						<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
							<input type="hidden" name="supplier_id" value="<?php echo $setId;?>">
							<div class="account_creation">
								
								<div class="required form-group">
									<?php
									if(mysqli_num_rows($resultSuppliers)>0)
									{	
										while($supplier=mysqli_fetch_array($resultSuppliers))
										{
											?>
											<div class="supplierinfo">
												<h3>Supplier : <?php echo $supplier['suppliername'];?></h3>
												<h4>Contact Name : <?php echo $supplier['contactname'];?></h4>
												<h4>Contact # : <?php echo $supplier['contactnumber'];?></h4>
												<h4>Address : <?php echo $supplier['address'];?></h4>
											</div>
										<?php
										}
									}
									?>
								</div>
								<div class="required form-group">
									<h4>Particulars</h4>
								</div>
								<?php
										$queryProducts = "SELECT * FROM tbl_product WHERE supplier_id = '".$_GET['supplier_id']."' AND quantity <= reorder_level";
										$resultProducts = mysqli_query($conn,$queryProducts);	
										if(mysqli_num_rows($resultProducts)>0)
										{	
											?>
											<table class='table table-hover table-responsive table-bordered'>
												<tr>
								                    <th class='textAlignLeft'>Product Name</th>
								                    <th>Unit Price</th>
								                    <th>Size</th>
								                    <th>Quantity Left</th>
								                    <th>Reorder</th>
								                    <th>Quantity to be added</th>
								                </tr>
											<?php
											while($product=mysqli_fetch_array($resultProducts))
											{
												?>
												<div class="supplierinfo">
													<tr>
							                        <td><?php echo $product['product_name'];?></td>
							                        <td><?php echo $product['price'];?></td>
							                        <td><?php echo $product['size'];?></td>
							                        <td><?php echo $product['quantity'];?></td>
							                        <td><?php echo $product['reorder_level'];?></td>
							                        <td><input type="text" name="addquantity[<?php echo $product['product_id'];?>]" class="form-control" required="true" value="5"></td>
							                    	</tr>
												</div>
											<?php
											}
											?>

											</table>
											<?php
										}
								?>

							</div>
											
							<div class="submit clearfix">	
								<button type="submit" name="generatePO" id="generatePO" class="btn btn-default button button-medium">
									<span>Generate Purchase Order<i class="icon-chevron-right right"></i></span>
								</button>
							</div>
						</form>
						</div>
		</div>
	</div>
 </div>
</div>



<?php
include_once("admin-footer.php");
?>
