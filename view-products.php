<?php
include_once("inc_header.php");
include_once("config.php");
include_once('account_session.php'); 
$action = isset($_GET['action']) ? $_GET['action'] : "";
$info = '';
if(isset($_GET['cp_id']) && isset($_GET['cart_id']) && isset($_GET['action']) && $_GET['action']=='remove'){
    $queryRemove = "DELETE FROM tbl_cart_product WHERE product_id='".$_GET['cp_id']."' && cart_id='".$_GET['cart_id']."'";
    $resultRemove = mysqli_query($conn,$queryRemove);

    $queryCount = "SELECT * FROM tbl_cart_product WHERE cart_id='".$_GET['cart_id']."'";
    $resultCount = mysqli_query($conn,$queryCount);
    if(mysqli_num_rows($resultCount)==0)
    {
        $queryRemoveCart = "DELETE FROM tbl_cart WHERE cart_id='".$_GET['cart_id']."'";
        $resultRemoveCart = mysqli_query($conn,$queryRemoveCart);
    }

}elseif(isset($_GET['cp_id']) && isset($_GET['cart_id']) && isset($_GET['action']) && $_GET['action']=='add'){
    /*check displayed product quantity*/
    $queryStock = "SELECT quantity FROM tbl_product WHERE product_id='".$_GET['cp_id']."'";
    $resultStock = mysqli_query($conn,$queryStock);
    if(mysqli_num_rows($resultStock)>0)
    {
        while($rowStock=mysqli_fetch_array($resultStock))
        {
            if($rowStock['quantity']>0){
                 $queryPlus = "UPDATE tbl_cart_product SET quantity=quantity+1 WHERE product_id='".$_GET['cp_id']."' && cart_id='".$_GET['cart_id']."'";
                 $resultPlus = mysqli_query($conn,$queryPlus);
                 $info ='Successfully add 1 quantity';
                 $queryMinusStock = "UPDATE tbl_product SET quantity=quantity-1 WHERE product_id='".$_GET['cp_id']."'";
                 $resultMinusStock = mysqli_query($conn,$queryMinusStock);
            }else{
                $info ='Unable to increase quantity. No more stocks left';
            }
        }
    }else{
        $info ='Unable to increase quantity. No more stocks left';
    }
   

}elseif(isset($_GET['cp_id']) && isset($_GET['cart_id']) && isset($_GET['action']) && $_GET['action']=='deduct'){
    /*check displayed product quantity*/
    $queryStock = "SELECT quantity FROM tbl_cart_product WHERE product_id='".$_GET['cp_id']."' && cart_id='".$_GET['cart_id']."'";
    $resultStock = mysqli_query($conn,$queryStock);
    if(mysqli_num_rows($resultStock)>0)
    {
        while($rowStock=mysqli_fetch_array($resultStock))
        {
            if($rowStock['quantity']>1){
                $queryMinus = "UPDATE tbl_cart_product SET quantity='".($rowStock['quantity']-1)."' WHERE product_id='".$_GET['cp_id']."' && cart_id='".$_GET['cart_id']."'";
                $resultMinus = mysqli_query($conn,$queryMinus);

                $queryAddStock = "UPDATE tbl_product SET quantity=quantity+1 WHERE product_id='".$_GET['cp_id']."'";
                $resultAddStock = mysqli_query($conn,$queryAddStock);
                $info ='Successfully decrease 1 quantity';
            }else{ $info ='Unable to decrease quantity. Only 1 product left';}
        }
    }else{
        $info ='Error. Kindly inform management';
    }
    
}else{

}


$query = "SELECT * FROM tbl_cart WHERE user_id=".$_SESSION["user_id"]." ORDER BY dateadded DESC";
$result = mysqli_query($conn,$query);

?>

<div id="account">
    
    <div class="columns-container">
    <div id="columns" class="container">


    <h1 class="page-heading">My Order History <?php if(mysqli_num_rows($result)>0){echo "( ".mysqli_num_rows($result)." )";}?></h1>
        <?php
        if($action=='order-submitted'){
            echo "<div class='alert alert-success'>";
                echo "Your order is already submitted!";
            echo "</div>";
        }
         ?>
        <?php
        if($info!=''){
            echo "<div class='alert alert-success'>";
                echo $info;
            echo "</div>";
        }
         ?>
       <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Transactions Panel</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">  
                <table class="table table-bordered table-hover"> 
                    <thead> 
                        <tr> 
                            <th>Order #</th> 
                            <th>Reference #</th> 
                            <th>Delivery Address</th> 
                            <th>Date Ordered</th> 
                            <th> Status</th> 
                            <th> Action</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                    <?php
                    if($result->num_rows>0)
                    {   
                        while($row=mysqli_fetch_array($result))
                        {
                            ?>
                            <tr> 
                                <th scope="row"><?php echo  date("Ymd",strtotime($row['dateadded'])).'-'.$row['cart_id'];?></th> 
                                <td><?php echo $row['referenceno'];?></td>
                                <td><?php echo $row['deliveryaddress'];?></td> 
                                <td><?php echo date("F d, Y H:i:s",strtotime($row['dateadded']));?></td> 
                                <td><?php 
                                    $deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
                                     "Order being processed", "In delivery", "Delivered", "Refund being processed", 
                                     "Refunded", "Order closed");

                                    echo $deliverystatus[$row['delivery_status']];
                                    ?></td>
                                <td>
                                    <a class="cart"  data-id="<?php echo $row['cart_id'];?>" href="#" style="color:#06425C;"> <i class="fa fa-edit" aria-hidden="true"></i> View Products</a> | 
                                    <a href="upload-receipt.php?id=<?php echo $row['cart_id'];?>" style="color:#814374;"> <i class="fa fa-image" aria-hidden="true"></i> Upload Payment Receipt</a>
                                </td> 
                                </td>  
                            </tr>
                            <tr> 
                                <td colspan="6" class="cartDisplay" id="<?php echo "cartproduct_".$row['cart_id'];?>">
                                    <?php
                                      $queryProducts = "SELECT tp.product_name as product_name, cp.price as price, cp.refunded as refunded, cp.refund_amount as refund_amount, cp.quantity as quantity, tp.quantity as tquantity, cp.id_product as cp_id,tc.categoryname as categoryname FROM tbl_cart_product cp  LEFT JOIN tbl_product tp ON cp.id_product = tp.id_product 
                                          LEFT JOIN tbl_brand tc ON tp.brand_id = tc.brand_id
                                          WHERE cart_id='".$row['cart_id']."'";
                                      $resultProducts = mysqli_query($conn,$queryProducts); 
                                         echo "<h4>Products <span class='pull-right'><a href='view-receipt.php?id=".$row['cart_id']."' target='_blank' class='btn btn-primary'>Print Receipt</a></span></h4>
                                                <table class='table table-hover table-responsive table-bordered'>";
                                            // our table heading
                                         echo "<tr>";
                                                echo "<th class='textAlignLeft'>Product Name</th>";
                                                echo "<th>Brand</th>";
                                                echo "<th>Quantity</th>";
                                                echo "<th>Unit Price</th>";
                                                echo "<th>Total Price</th>";
                                            echo "</tr>";
                                            $total_price=0;
                                            while($prodRefund=mysqli_fetch_array($resultProducts)){
                                                
                                                echo "<tr>";
                                                    echo "<td>{$prodRefund['product_name']}";
                                                        if($prodRefund['refunded']==1){
                                                          echo"<span class='pull-right red' > Refunded ( - ".$prodRefund['refund_amount'].")</span>";
                                                        }
                                                    echo"</td>";
                                                    echo "<td>{$prodRefund['brandname']} ";
                                                    echo "<td>{$prodRefund['quantity']} ";
                                                    if($row['delivery_status']<4){
                                                    echo"<span class='deleteItem pull-right' >
                                                        <a href='account.php?cp_id=".$prodRefund['cp_id']."&cart_id=".$row['cart_id']."&action=remove' class='del' title='Remove item'> 
                                                            <i class='fa fa-times' aria-hidden='true'></i>
                                                        </a></span>  
                                                        <span class='addQty pull-right'> 
                                                        <a href='account.php?cp_id=".$prodRefund['cp_id']."&cart_id=".$row['cart_id']."&action=add'>
                                                            <i class='fa fa-plus-circle' aria-hidden='true'></i>
                                                        </a></span> 
                                                        <span class='minusQty pull-right'> 
                                                        <a href='account.php?cp_id=".$prodRefund['cp_id']."&cart_id=".$row['cart_id']."&action=deduct'>
                                                            <i class='fa fa-minus-circle' aria-hidden='true'></i>
                                                        </a></span>";
                                                        }
                                                    echo"</td>";
                                                    echo "<td>".number_format($prodRefund['price'],2)."</td>";
                                                     echo "<td>".number_format($prodRefund['quantity'] * $prodRefund['price'],2)."</td>";
                                                echo "</tr>";
                                                $total_price+= ($prodRefund['quantity'] * $prodRefund['price']);
                                            }
                                            if($row['delivery_fee']){
                                                 echo "<tr>";
                                                    echo "<td><b>Delivery Fee</b></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td >PHP ".number_format($row['delivery_fee'],2)."</td>";
                                                echo "</tr>";
                                                 $total_price+= ($row['delivery_fee']);
                                            }
                                            echo "<tr>";
                                                    echo "<td class='total' ><b>Overall Total</b></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td></td>";
                                                    echo "<td class='total'>PHP ".number_format($total_price,2)."</td>";
                                                echo "</tr>";
                                     
                                        echo "</table>";
                                    ?>
                                </td> 
                            </tr>
                            <?php
                        }
                    }else{
                        echo"<tr> <th scope='row' colspan='5'>You have no orders yet!</th> </tr>"; 
                    }
                    ?>
                    
                    </tbody> 
                </table>
            </div>
        </div>
        </div>
      </div>
    </div>

</div>
<script>
jQuery(document).ready(function($) {
    $(".del").click(function(){
        if (!confirm("Do you want to delete")){
          return false;
        }
    });

    $( ".cart" ).click(function() {
        var id = $(this).attr("data-id");
      $( "#cartproduct_"+id ).slideToggle( "slow", function() {
        // Animation complete.cart_
      });
    });
});
</script>
<?php
include_once("inc_footer.php");
?>
</body>
</html>