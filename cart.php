<?php
include_once("inc_header.php");
include_once("config.php");
 
$action = isset($_GET['action']) ? $_GET['action'] : "";
$name = isset($_GET['name']) ? $_GET['name'] : "";
?>

<div id="page">
    
    <div class="columns-container">
    <div id="columns" class="container">
    <div class="row">
       
        <div id="center_column" class="center_column col-xs-12 col-sm-12">
        <h1>Cart Items:</h1>
        <?php
        if($action=='removed'){
            echo "<div class='alert alert-info'>";
                echo "<strong>{$name}</strong> was removed from your cart!";
            echo "</div>";
        }
         
        $cookie = isset($_COOKIE['cart_items_cookie']) ?$_COOKIE['cart_items_cookie']: "";
        $cookie = stripslashes($cookie);
        $saved_cart_items = json_decode($cookie, true);
        if(count($saved_cart_items)>0){
            
            $ids = "";
            foreach($saved_cart_items as $id=>$name){
                $ids = $ids . $id . ",";
            }
            // remove the last comma
            $ids = rtrim($ids, ',');
            //start table
            echo "<table class='table table-hover table-responsive table-bordered'>";
         
                // our table heading
                echo "<tr>";
                    echo "<th class='textAlignLeft'>Product Name</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Unit Price</th>";
                    echo "<th>Total Price</th>";
                    echo "<th>Action</th>";
                echo "</tr>";
         
                $query = "SELECT product_id, product_name, price FROM tbl_product WHERE product_id IN ({$ids}) ORDER BY product_name";
                
                $result = mysqli_query($conn,$query);
                $total_price=0;
                while($row=mysqli_fetch_array($result)){
         
                    echo "<tr>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$saved_cart_items[$row['product_id']]['quantity']} </td>";
                        echo "<td>".number_format($saved_cart_items[$row['product_id']]['price'],2)."</td>";
                         echo "<td>".number_format($saved_cart_items[$row['product_id']]['quantity'] * $saved_cart_items[$row['product_id']]['price'],2)."</td>";
                        echo "<td>";
                            echo "<a href='product-remove-to-cart.php?id={$id}&name={$row['product_name']}' class='btn btn-danger'>";
                                echo "Delete item";
                            echo "</a>";
                        echo "</td>";
                    echo "</tr>";
         
                    $total_price+= ($saved_cart_items[$row['product_id']]['quantity'] * $saved_cart_items[$row['product_id']]['price']);
                }
         
                echo "<tr>";
                        echo "<td class='total'><b>Total</b></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td class='total'>PHP ".number_format($total_price,2)."</td>";
                        echo "<td>";
                        echo "</td>";
                    echo "</tr>";
         
            echo "</table>";
            echo "<a href='checkout.php' class='btn btn-success'>";
                echo "Checkout";
            echo "</a>";
        }
         
        else{
            echo "<div class='alert alert-danger'>";
                echo "<strong>No products found</strong> in your cart!";
            echo "</div>";
            echo "<a href='index.php' class='centered'>Go Shopping</a>";
        }
         ?>
         </div>
      </div>
    </div>
</div>
<?php
include_once("inc_footer.php");
?>
</body>
</html>