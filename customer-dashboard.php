<?php
include_once("inc_header.php");
include_once("config.php");
include_once('account_session.php'); 
$action = isset($_GET['action']) ? $_GET['action'] : "";
$info = '';
$delete_info = '';
if(isset($_GET['orderid']) && $_GET['action']=='delete')
{
    $queryRemove = "DELETE FROM tbl_cartproduct WHERE cart_id='".$_GET['orderid']."'";
    $resultRemove = mysqli_query($conn,$queryRemove);

    $queryRemoveCart = "DELETE FROM tbl_cart WHERE cart_id='".$_GET['orderid']."'";
    $resultRemoveCart = mysqli_query($conn,$queryRemoveCart);
    $delete_info = 'Order successfully deleted';
}

if(isset($_GET['cp_id']) && isset($_GET['cart_id']) && isset($_GET['action']) && $_GET['action']=='remove'){
    $queryRemove = "DELETE FROM tbl_cartproduct WHERE product_id='".$_GET['cp_id']."' && cart_id='".$_GET['cart_id']."'";
    $resultRemove = mysqli_query($conn,$queryRemove);

    $queryCount = "SELECT * FROM tbl_cartproduct WHERE cart_id='".$_GET['cart_id']."'";
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
                 $queryPlus = "UPDATE tbl_cartproduct SET quantity=quantity+1 WHERE product_id='".$_GET['cp_id']."' && cart_id='".$_GET['cart_id']."'";
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


    <h3>My Orders Record </h3>
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
         <?php
        if(isset($delete_info) && $delete_info!=''){
            echo "<div class='alert alert-warning'>";
                echo $delete_info;
            echo "</div>";
        }
         ?>
       <div class="panel panel-default">
            <div class="table-responsive">  
                <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
                    <thead> 
                        <tr> 
                            <th>Order #</th> 
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
                                <th scope="row"><?php echo $row['cart_id'];?></th> 
                                <td><?php echo $row['deliveryaddress'];?></td> 
                                <td><?php echo date("M d, Y H:i:s",strtotime($row['dateadded']));?></td> 
                                <td><?php echo $row['delivery_status'];?></td>
                                </td>  
                                 <td>
                                 <?php if($row['delivery_status']!='Delivered'){ ?>
                                 <a href="customer-dashboard.php?action=delete&orderid=<?php echo $row['cart_id'];?>">Delete Order</a>
                                 <?php } ?>
                                 </td> 
                            </tr>
                            <?php
                        }
                    }else{
                        echo"<tr> <th scope='row' colspan='6'>You have no orders yet!</th> </tr>"; 
                    }
                    ?>
                    
                    </tbody> 
                </table>
            </div>
        </div>
      </div>
    </div>

</div>

<?php
include_once("inc_footer.php");
?>
</body>
</html>