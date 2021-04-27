<?php
// initialize empty cart items array
$cart_items=array();
 

if(isset($_POST['product_id']) && $_POST['product_id']!=null)
{
    // get the product id and name
    $id = isset($_POST['product_id']) ? $_POST['product_id'] : "";
    $qty = isset($_POST['qty']) ? $_POST['qty'] : "";
    $price = isset($_POST['price']) ? $_POST['price'] : ""; 
    // add new item on array
    $cart_items[$id]['quantity']=$qty;
    $cart_items[$id]['price']=$price;

    // read the cookie
    $cookie =  isset($_COOKIE['cart_items_cookie']) ?$_COOKIE['cart_items_cookie']: "";
    $cookie = stripslashes($cookie);
    $saved_cart_items = json_decode($cookie, true);
     
    // if $saved_cart_items is null, prevent null error
    if(!$saved_cart_items){
        $saved_cart_items=array();
    }
 
    // check if the item is in the array, if it is, do not add
    if(array_key_exists($id, $saved_cart_items)){
        // redirect to product list and tell the user it was already added to the cart
        header('Location: product.php?action=exists&id=' . $id);
    }
    else{
        // if cart has contents
        if(count($saved_cart_items)>0){
            foreach($saved_cart_items as $key=>$value){
                // add old item to array, it will prevent duplicate keys
                $cart_items[$key]=$value;
            }
        }
        
        // put item to cookie
        $json = json_encode($cart_items, true);
        setcookie('cart_items_cookie', $json);
        // redirect
        header('Location: product.php?action=added&id=' . $id);
    }
}
else{
    echo"<script>window.history.back();</script>";
}
?>