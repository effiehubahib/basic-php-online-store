<?php
include_once("config.php");
// initialize empty cart items array
session_start();

$cart_items=array();

// read the cookie
$cookie =  isset($_COOKIE['cart_items_cookie']) ?$_COOKIE['cart_items_cookie']: "";
$cookie = stripslashes($cookie);
$saved_cart_items = json_decode($cookie, true);
 
// if $saved_cart_items is null, prevent null error
if(!$saved_cart_items){
    header('Location: cart.php');
}
else{
        $userid = $_SESSION["user_id"];
        $deliveryaddress = $_POST['addressname'];
        $cart_id = '';
        $dateadded = date("Y-m-d H:i:s");
        //save Cart Details
        $query = "INSERT INTO tbl_cart(user_id, deliveryaddress,delivery_status,dateadded) 
                        VALUES('$userid','$deliveryaddress','For delivery','$dateadded')";
       
        $result = mysqli_query($conn,$query);
        if ($result)
        {   
            //get Cart ID created
             $query = "SELECT LAST_INSERT_ID()";
             $result = mysqli_query($conn,$query);
              while($row_id=mysqli_fetch_array($result)){
                $cart_id = $row_id['0'];
              }
        }
       
    // if cart has contents
        foreach($saved_cart_items as $key=>$value){

            $queryINSERTPRODUCT = "INSERT INTO tbl_cartproduct(cart_id, product_id,quantity,price) 
                    VALUES('$cart_id','".$key."','".$value['quantity']."','".$value['price']."')";

            mysqli_query($conn,$queryINSERTPRODUCT);
            //update quantity of display product in table
            $queryUPDATEPRODUCT = "UPDATE tbl_product SET quantity = quantity-".$value['quantity']." WHERE product_id=$key";
            mysqli_query($conn,$queryUPDATEPRODUCT);

        }

    unset($_COOKIE['cart_items_cookie']);
    // empty value and expiration one hour before
    $res = setcookie('cart_items_cookie', '', time() - 3600);
    // redirect
    header('Location: customer-dashboard.php?action=order-submitted');
}
 
?>