<?php

include_once("admin-config.php");
include_once("admin-header.php");

if(!isset($_GET['id']))
{
    header("Location: supplier-purchase-order.php"); 
}


if(isset($_GET['bid']) && isset($_GET['action']) && $_GET['action']=='delete')
{
  $queryAmountDelete = "DELETE FROM amountpaid WHERE id='".$_GET['bid']."'";
  $resultAmountDelete = mysqli_query($conn,$queryAmountDelete);

  header("Location: view-customer-order.php?id=".$_GET['id']); 
}


$po_id = $_GET['id'];
$delivery_update=false;
$replace_msg = '';
	if(isset($_POST['updateDelivery'])){
		$delivery_status = $_POST['delivery_status'];
		if (empty($delivery_status) ) 
	 	{
	 		$error="Please select delivery status";
	 	}else{

	 		      $queryStatus = "UPDATE tbl_purchase_order SET status = '$delivery_status' WHERE po_id = '$po_id'";

            $po_id = $_GET['id'];
            $result2 = mysqli_query($conn,$queryStatus);
            $delivery_update=true;
            if($delivery_status=='Delivered'){
              $queryPODetails = "SELECT * FROM tbl_purchase_order_details WHERE po_id='$po_id'";
              $resultPODetails = mysqli_query($conn,$queryPODetails);
              while($prod=mysqli_fetch_array($resultPODetails)){
                 
                  $queryAddProduct = "UPDATE tbl_product SET quantity = quantity + ".$prod['quantity']." WHERE product_id='".$prod['product_id']."'"; 
                  $resultAddProduct = mysqli_query($conn,$queryAddProduct); 
              }

               echo "<script>alert('Product successfully delivered and added');window.location='supplier-purchase-order.php'</script>";

            }else{
                echo "<script>alert('Purchase Order Status Updated');window.location='supplier-purchase-order.php'</script>";
            }   
	 	}
	}

if(isset($_GET['product_id']) && isset($_GET['action']) && $_GET['action']=='deleteReplace' && $_GET['rid'])
  {
    $queryDeleteReplaceItem = "DELETE FROM tbl_replace WHERE cart_id='".$cart_id."' && product_id='".$_GET['product_id']."' && replace_id='".$_GET['rid']."'";
    $resultDeleteReplaceItem = mysqli_query($conn,$queryDeleteReplaceItem);
    $replace_msg = "Sucessfully removed replaced item";
  }

  $queryPO = "SELECT * FROM tbl_purchase_order LEFT JOIN tbl_supplier ON tbl_purchase_order.supplier_id= tbl_supplier.supplier_id WHERE po_id='$po_id'";
  $resultPO = mysqli_query($conn,$queryPO);

	
?>

<div id="orders">
  <div class="container">
    <div class="row">
      <div id="center_column" class="center_column col-xs-10 col-sm-10">
        <h3 class="page-heading"> Purchase Order #<?php echo $_GET['id'];?></h3>
    
    <?php

if(mysqli_num_rows($resultPO)>0){

?>    
    <div class="panel panel-primary">
      <?php if($delivery_update==true)
            {
              echo '<div class="alert alert-success"> Order Information updated</div>';
            }
      ?>
      <div class="panel-body">
        <?php
            while($resultPOInfo=mysqli_fetch_array($resultPO)){
               $delivery = $resultPOInfo['status'];
          ?>
          <div class="col-xs-10 col-sm-8 col-md-10">
            <h4>Supplier Name: <strong><?php echo $resultPOInfo['suppliername'];?></strong></h4>
            <h4>Address: <strong><?php echo $resultPOInfo['address'];?></strong></h4>
            <h4>Contact Name: <strong><?php echo $resultPOInfo['contactname'];?></strong></h4>
            <h4>Contact No.: <strong><?php echo $resultPOInfo['contactnumber'];?></strong></h4>
          </div>

        <?php

        ?>
          <div class="col-xs-12 col-sm-8 col-md-4">
              
              <div class="delivery_stat">
              <h4>Delivery Status:</h4>
              <?php if($resultPOInfo['status']=="Pending"){?>  
              <form action="" method="post" id="payment_receipt_form" class="std" enctype="multipart/form-data">
              <?php //if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"]=="Staff")){?>
                <div class="form-group">
                  <label>Delivery Status</label>
                  <select name="delivery_status" id="delivery_status" class="form-control" required="true">
                    <?php 
                      $selected = '';
                      $po_delivery_status = array("Pending","Delivered");
                      foreach($po_delivery_status as $key =>$status){
                        if($delivery ==$status)
                          $selected = "selected";
                        else
                          $selected = '';
                      
                        echo "<option value='".$status."' ".$selected." >".$status."</option>";

                      }
                    ?>
                  </select>

                </div>
                
                <div class="form-group">
                   <input type="hidden" name="po_id" value="<?php echo $resultPOInfo['po_id'];?>" class="form-control" required="true">
                </div>
                <button type="submit" name="updateDelivery" class="btn btn-primary">Save</button>
                <?php //} ?>
              </form>
              <?php 
                }else{
                  echo"<h2>Delivered</h2>";
                }
              ?>
              </div>  
          </div>
      <?php 
          }
        ?>
      </div>
    </div>
      

    <?php

         echo "<div class='fluid-container'><h4>Products</h4>
                <table class='table table-hover table-responsive table-bordered'>";
            // our table heading
         echo "<tr>";
                echo "<th class='textAlignLeft'>Product Name</th>";
                echo "<th>Size</th>";
                echo "<th>Added Quantity</th>";
                echo "<th>Unit Price</th>";
                echo "<th>Total Price</th>";
            echo "</tr>";
            $total_price=0;
            $queryPODetails = "SELECT *, tbl_purchase_order_details.quantity as addedquantity FROM tbl_purchase_order_details LEFT JOIN tbl_product ON tbl_purchase_order_details.product_id= tbl_product.product_id WHERE po_id='$po_id'";
            $resultPODetails = mysqli_query($conn,$queryPODetails);

            while($prod=mysqli_fetch_array($resultPODetails)){
                echo "<tr>";
                    echo "<td>{$prod['product_name']}";
                    echo"</td>";
                     echo "<td>{$prod['size']} </td>";
                    echo "<td>{$prod['addedquantity']} </td>";
                    echo "<td>".number_format($prod['price'],2)."</td>";
                     echo "<td style='text-align:right;'>".number_format($prod['addedquantity'] * $prod['price'],2)."</td>";
                echo "</tr>";
     
                $total_price+= ($prod['quantity'] * $prod['price']);
            }
                                   
            echo "<tr>";
                    echo "<td class='total' ><b>Overall Total</b></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                     echo "<td></td>";
                    echo "<td class='total' style='text-align:right;'>PHP ".number_format($total_price,2)."</td>";
                echo "</tr>";

        echo "</table></div>";

    ?>
 <?php
 }else{
  header("Location: orders.php");
 }

 ?>
      </div>
    </div>
  </div>
</div>
<script>
 $(document).ready(function() {
    var firstValue = $( "select#delivery_status option:selected" ).text();
   /* check first value of payment delivery status */
    if(firstValue == 'Payment received'){
      $("#amountpaid").attr("required", "true");
      $("#divAmountPaid").show();
    }else{
      $("#divAmountPaid").hide();
    } 
    /* check value of payment delivery status when dropdown is changed */
    $( "select#delivery_status" ).on('change', function() {
          var str = "";
          $( "select#delivery_status option:selected" ).each(function() {
            
            var selOption = $(this).text();
            if(selOption == 'Payment received'){
              $("#amountpaid").attr("required", "true");
              $("#divAmountPaid").show();
            }else{
              $("#amountpaid").removeAttr("required");
               $("#divAmountPaid").hide();
            }
          });
    })
 });
</script>
<?php
include_once("admin-footer.php");
?>