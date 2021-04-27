<?php

include_once("admin-config.php");
include_once("admin-header.php");

if(!isset($_GET['id']))
{
    header("Location: orders.php"); 
}


if(isset($_GET['bid']) && isset($_GET['action']) && $_GET['action']=='delete')
{
  $queryAmountDelete = "DELETE FROM amountpaid WHERE id='".$_GET['bid']."'";
  $resultAmountDelete = mysqli_query($conn,$queryAmountDelete);

  header("Location: view-customer-order.php?id=".$_GET['id']); 
}






$cart_id = $_GET['id'];
$delivery_update=false;
$replace_msg = '';
	if(isset($_POST['updateDelivery'])){
		$delivery_status = $_POST['delivery_status'];
		if (empty($delivery_status) ) 
	 	{
	 		$error="Please select delivery status";
	 	}else{
	 		      $queryStatus = "UPDATE tbl_cart SET delivery_status = '$delivery_status' WHERE cart_id = '$cart_id'";

            $id_user = $_POST['id_user'];
            $id_cart = $_GET['id'];
            $result2 = mysqli_query($conn,$queryStatus);
            $delivery_update=true;

	 	}
	}

if(isset($_GET['product_id']) && isset($_GET['action']) && $_GET['action']=='deleteReplace' && $_GET['rid'])
  {
    $queryDeleteReplaceItem = "DELETE FROM tbl_replace WHERE cart_id='".$cart_id."' && product_id='".$_GET['product_id']."' && replace_id='".$_GET['rid']."'";
    $resultDeleteReplaceItem = mysqli_query($conn,$queryDeleteReplaceItem);
    $replace_msg = "Sucessfully removed replaced item";
  }

	$query = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.user_id= tbl_user.user_id WHERE cart_id='$cart_id'";
	$result = mysqli_query($conn,$query);

?>

<div id="orders">
  <div class="container">
    <div class="row">
      <div id="center_column" class="center_column col-xs-10 col-sm-10">
        <h3 class="page-heading">Order #<?php echo $_GET['id'];?></h3>
    
    <?php

if(mysqli_num_rows($result)>0){

?>    
    <div class="panel panel-primary">
      <?php if($delivery_update==true)
            {
              echo '<div class="alert alert-success"> Order Information updated</div>';
            }
      ?>
      <div class="panel-body">
        <?php
            while($cartInfo=mysqli_fetch_array($result)){
               $delivery   = $cartInfo['delivery_status'];
          ?>
          <div class="col-xs-10 col-sm-8 col-md-10">
            <h4>Customer Name: <strong><?php echo $cartInfo['lastname'].", ".$cartInfo['firstname'];?></strong></h4>
            <h4>Address: <strong><?php echo $cartInfo['address'];?></strong></h4>
            <h4>Contact No.: <strong><?php echo $cartInfo['contact_no'];?></strong></h4>
            <h4>Email: <strong><?php echo $cartInfo['email'];?></strong></h4>
            <br/>
            <h4>Delivery Address: <strong><?php echo $cartInfo['deliveryaddress'];?></strong></h4>
          </div>

        <?php

        ?>
          <div class="col-xs-12 col-sm-8 col-md-4">
              
                
              <form action="" method="post" id="payment_receipt_form" class="std" enctype="multipart/form-data">
              <?php //if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"]=="Staff")){?>
                <div class="form-group">
                  <label>Delivery Status</label>
                  <select name="delivery_status" id="delivery_status" class="form-control" required="true">
                    <?php 
                      $selected = '';
                      foreach($deliverystatus as $key =>$status){
                        if($cartInfo['delivery_status']==$status)
                          $selected = "selected";
                        else
                          $selected = '';
                      
                        echo "<option value='".$status."' ".$selected." >".$status."</option>";

                      }
                    ?>
                  </select>

                </div>
                <div class="modal fade" id="myModal"  role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Copy and Paste Reference</h4>
                      </div>
                      <div class="modal-body">
                        <p><?php echo strtotime(date("Y-m-d H:i:s"));?></p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                
                <div class="form-group">
                   <input type="hidden" name="id_user" value="<?php echo $cartInfo['id_user'];?>" class="form-control" required="true">
                </div>
                
                <button type="submit" name="updateDelivery" class="btn btn-primary">Save</button>
                <?php //} ?>
              </form>
          </div>
      <?php 
          }
        ?>
      </div>
    </div>
      
          <?php 
              
              /* update refunded column */
              if(isset($_GET['prod_id']) && isset($_GET['action']) && isset($_GET['amt']) && $_GET['action']=='refund' && $_GET['amt'])
              {
                $queryTotalPaid = "SELECT sum(amount) as totalAmt FROM amountpaid WHERE cart_id='".$cart_id."'";
                
                $resultTotalPaid = mysqli_query($conn, $queryTotalPaid);
                $tAmt = 0;
                if(mysqli_num_rows($resultTotalPaid)>0)
                {
                  while($rTP = mysqli_fetch_array($resultTotalPaid)){
                     $tAmt = $rTP['totalAmt'];
                    }
                }
                
                $amt = $_GET['amt'];
                if($tAmt >= $amt){
                  $queryRefundProduct = "UPDATE tbl_cart_product SET refunded='1',refund_amount='$amt',refund_date = NOW() WHERE id_cart='".$cart_id."' && id_product='".$_GET['prod_id']."'"; 
                  $resultRefundProduct = mysqli_query($conn,$queryRefundProduct);
                }else{
                  echo "<script>alert('Unable to refund. Amount paid is lesser than refund amount');</script>";
                }
              }
              if(isset($_GET['prod_id']) && isset($_GET['action']) && $_GET['action']=='removerefund')
              {
                $queryRefundProduct = "UPDATE tbl_cart_product SET refunded='0',refund_amount='0' WHERE id_cart='".$cart_id."' && id_product='".$_GET['prod_id']."'"; 
                $resultRefundProduct = mysqli_query($conn,$queryRefundProduct);
              }
    ?>

    <?php
       $queryProducts = "SELECT tp.product_id as product_id, tp.product_name as product_name, cp.price as price, cp.quantity as quantity,tc.brandname as brandname FROM tbl_cartproduct cp 
          LEFT JOIN tbl_product tp ON cp.product_id = tp.product_id 
          LEFT JOIN tbl_brand tc ON tp.brand_id = tc.brand_id     
          WHERE cart_id='".$cart_id."'";

      $resultProducts = mysqli_query($conn,$queryProducts);
         echo "<div class='fluid-container'><h4>Products</h4>
                <table class='table table-hover table-responsive table-bordered'>";
            // our table heading
         echo "<tr>";
                echo "<th class='textAlignLeft'>Product Name</th>";
                echo "<th>Category</th>";
                echo "<th>Quantity</th>";
                echo "<th>Unit Price</th>";
                echo "<th>Total Price</th>";
            echo "</tr>";
            $total_price=0;
            while($prod=mysqli_fetch_array($resultProducts)){
                echo "<tr>";
                    echo "<td>{$prod['product_name']}";
                    echo"</td>";
                     echo "<td>{$prod['brandname']} </td>";
                    echo "<td>{$prod['quantity']} </td>";
                    echo "<td>".number_format($prod['price'],2)."</td>";
                     echo "<td style='text-align:right;'>".number_format($prod['quantity'] * $prod['price'],2)."</td>";
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