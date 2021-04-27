<?php

include_once("admin_config.php");

if(!isset($_GET['id']) || !isset($_GET['prod_id']))
{
    header("Location: orders.php"); 
}
if(isset($_POST['confirmReplace']) )
{
  $totalReplaceAmount = 0;
      $id_product = $_POST['id_product'];
      $quantity = $_POST['quantity'];
      $id_cart = $_POST['cart_id'];
      $replaced_amount = $_POST['replaced_amount'];

              /* get total amount of all replace items */
              $queryTotalReplace = "SELECT sum(amount * quantity) as totalAmt FROM tbl_replace WHERE id_cart='".$id_cart."' && id_product='".$id_product."'";
                 
                $resultTotalReplace  = mysqli_query($conn, $queryTotalReplace);

                $tAmt = 0;
                if(mysqli_num_rows($resultTotalReplace)>0)
                {
                  while($rTA = mysqli_fetch_array($resultTotalReplace)){
                     $tAmt = $rTA['totalAmt'];
                    }
                }            
             
            list($replace_id,$amount) = explode("-",$_POST['replace_id']);

             $totalReplaceAmount = $tAmt + ($amount * $quantity);
            if($totalReplaceAmount<=$replaced_amount){
              
                $queryReplace = "INSERT INTO tbl_replace(id_cart, id_product, replace_id, quantity, amount) VALUES('$id_cart', '$id_product','$replace_id','$quantity','$amount')";
                $resultReplace = mysqli_query($conn,$queryReplace);
                header("Location: view-customer-order.php?id=".$id_cart); 
              }
              else{
                echo "<script>alert('Unable to replace. Amount of item to replace is greater than replaced item');</script>";
              }

    
}



include_once("admin_header.php");


$cart_id = $_GET['id'];
$prod_id = $_GET['prod_id'];



	$query = "SELECT tbl_cart_product.price as price, tbl_cart_product.quantity as quantity,tbl_product.product_name as product_name, tbl_cart_product.id_cart as id_cart 
          FROM tbl_cart_product LEFT JOIN tbl_product ON tbl_cart_product.id_product= tbl_product.id_product 
          WHERE id_cart='$cart_id' && tbl_cart_product.id_product='$prod_id'";

	$result = mysqli_query($conn,$query);

  $queryProduct = "SELECT * FROM tbl_product WHERE product_status=1 && id_product != $prod_id ORDER BY id_product DESC";
  $resultProduct = mysqli_query($conn,$queryProduct);

?>

<div id="admin">
    
 <div class="columns-container">
    <div id="columns" class="container">
 <?php
 $shippingfee = 0;
 $delivery = '';
 $itemAmt = 0;
if(mysqli_num_rows($result)>0){
?>    
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Replace Product</h3>
      </div>
      <div class="panel-body">
      	<?php
      	  	while($refundProdInfo=mysqli_fetch_array($result)){
      	  ?>
	      	<div class="col-xs-12 col-sm-8 col-md-12">
	      		<h3>Product Name: <strong><?php echo $refundProdInfo['product_name'];?></strong></h3>
	      	  <h4>Price: <strong><?php echo number_format($refundProdInfo['price'],2);?></strong></h4>
            <h4>Quantity: <strong><?php echo $refundProdInfo['quantity'];?></strong></h4>
             <h4>Amount: <strong><?php echo number_format($refundProdInfo['quantity'] * $refundProdInfo['price'],2);?></strong></h4>
	      	  
            <h3>Replace with:</h3>
            <?php 
            $itemAmt = $refundProdInfo['quantity'] * $refundProdInfo['price'];

              if(mysqli_num_rows($resultProduct)>0){
            ?>
            <form method="POST">
                  <input type="hidden" name="cart_id" value="<?php echo $cart_id;?>">
                  <input type="hidden" name="id_product" value="<?php echo $prod_id;?>">
                  <input type="hidden" name="replaced_amount" value="<?php echo $itemAmt;?>">
                  <select class="input-sm" name="replace_id" id="replace_id">
                    <?php 
                      while($productlist=mysqli_fetch_array($resultProduct)){
                        echo "<option value='".$productlist['id_product']."-".$productlist['price']."'>".$productlist['product_name']." (".$productlist['price'].")</option>";
                      }
                    ?>
                  </select>
                  <input type="text" class="input-sm" name="quantity" id="quantity"  required="true" placeholder="Quantity" onkeyup="validateNumOnly(this,'b')">
                  <input type="submit" name="confirmReplace" value="Confirm Replace" >
            </form>
            <?php 
            }
            ?>
          </div>
      	<?php
      
      	  }
      	?>
          
      </div>
    </div>
      

 <?php
 }else{
 	header("Location: orders.php");
 }

 ?>
    </div>
 </div>
</div>
