<?php
include_once("admin-config.php");

    $queryReorderProducts = "SELECT * FROM tbl_product LEFT JOIN tbl_brand ON tbl_product.brand_id=tbl_brand.brand_id
            LEFT JOIN tbl_supplier ON tbl_product.supplier_id = tbl_supplier.supplier_id
            WHERE quantity<=reorder_level";
    $resultReorderProducts = mysqli_query($conn,$queryReorderProducts);

    $queryOrderFromCustomer = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.user_id= tbl_user.user_id WHERE deleteStatus=0 AND delivery_status= 0 ORDER BY cart_id DESC";
    $resultOrderFromCustomer = mysqli_query($conn,$queryOrderFromCustomer);
    $queryPurchaseOrder = "SELECT * FROM tbl_purchase_order WHERE status='Pending'";
    $resultPurchaseOrder = mysqli_query($conn,$queryPurchaseOrder);
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Arome System Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="../css/w3.css">
    <!--<link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="../js/jquery-ui.css">
    <link href="../assets/datatables/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="../assets/AdminThemes/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/AdminThemes/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../assets/AdminThemes/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../assets/AdminThemes/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                  <img src="../assets/images/aptslogo.png" alt="logo" style="height: 20px; width: 70px; margin-top: 15px;"><a class="navbar-brand" href="index.php">Arome PH</a>              
            </div>

            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
               
               
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i>Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i>Dashboard</a>
                    </li>
                   <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#purchase"><i class="fa fa-fw fa-arrows-v"></i> Purchase Order <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="purchase" class="collapse">
                           <li>
                                <a href="orders.php"><i class="fa fa-fw fa-desktop"></i> Customer
                                 <span class="label label-default"><?php
                                    echo mysqli_num_rows($resultOrderFromCustomer);
                                ?></span></a>
                            </li>
                            <li>
                                <a href="supplier-purchase-order.php"><i class="fa fa-fw fa-desktop"></i> Supplier
                                 <span class="label label-default"><?php
                                    echo mysqli_num_rows($resultPurchaseOrder);
                                ?></span></a>
                            </li>
                        </ul>
                    </li>
                    <!--
                    <li>
                        <a href="supplied-order.php"><i class="fa fa-fw fa-desktop"></i> Supplied Order</a>
                    </li>
                -->
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#soldproducts"><i class="fa fa-fw fa-arrows-v"></i> Sold Products <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="soldproducts" class="collapse">
                            <li>
                                <a href="sold-daily.php"><i class="fa fa-fw fa-edit"></i>Daily</a>
                            </li>
                            <!--<li>
                                <a href="sold-weekly.php"><i class="fa fa-fw fa-edit"></i>Weekly</a>
                            </li>
                            <li>
                                <a href="sold-yearly.php"><i class="fa fa-fw fa-edit"></i>Yearly</a>
                            </li>-->
                            <li>
                                <a href="sold-monthly.php"><i class="fa fa-fw fa-edit"></i>Monthly</a>
                            </li>
                            
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#products"><i class="fa fa-fw fa-arrows-v"></i> Products <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="products" class="collapse">
                            <li>
                                <a href="products.php"><i class="fa fa-fw fa-edit"></i>View Products</a>
                            </li>
                           <li>
                                <a href="product-add.php"><i class="fa fa-fw fa-edit"></i>Add New Product</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#sizes"><i class="fa fa-fw fa-arrows-v"></i> Sizes <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="sizes" class="collapse">
                            <li>
                                <a href="sizes.php"><i class="fa fa-fw fa-edit"></i>View Sizes</a>
                            </li>
                           <li>
                                <a href="size-add.php"><i class="fa fa-fw fa-edit"></i>Add New Size</a>
                            </li>
                        </ul>
                    </li>
                   <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#supplier"><i class="fa fa-fw fa-arrows-v"></i> Suppliers <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="supplier" class="collapse">
                            <li>
                                <a href="suppliers.php"><i class="fa fa-fw fa-edit"></i>View Suppliers</a>
                            </li>
                           <li>
                                <a href="supplier-add.php"><i class="fa fa-fw fa-edit"></i>Add New Suppliers</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#brands"><i class="fa fa-fw fa-arrows-v"></i> Brand <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="brands" class="collapse">
                            <li>
                                <a href="brands.php"><i class="fa fa-fw fa-edit"></i>View Brands</a>
                            </li>
                            <li>
                                <a href="brand-add.php"><i class="fa fa-fw fa-edit"></i>Add New Brand</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#customers"><i class="fa fa-fw fa-arrows-v"></i> Customers <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="customers" class="collapse">
                            <li>
                                <a href="customers.php?status=1"><i class="fa fa-fw fa-edit"></i>List Active Customers</a>
                            </li>
                            <li>
                                <a href="customers.php?status=0"><i class="fa fa-fw fa-edit"></i>List Deleted Customers</a>
                            </li>
                        </ul>
                    </li>
                    <?php 
                        if($_SESSION["user_type"]=="Admin"){
                     ?>       
                     <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#staff"><i class="fa fa-fw fa-arrows-v"></i> Staff <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="staff" class="collapse">
                            <li>
                                <a href="staff-add.php"><i class="fa fa-fw fa-edit"></i>Add New Staff</a>
                            </li>
                            <li>
                                <a href="staff.php?status=1"><i class="fa fa-fw fa-edit"></i>List Active Staff</a>
                            </li>
                             <li>
                                <a href="staff.php?status=0"><i class="fa fa-fw fa-edit"></i>List Inactive Staff</a>
                            </li>
                            <li>
                                <a href="staff.php?status=-1"><i class="fa fa-fw fa-edit"></i>List Deleted Staff</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                        }
                    ?>
                     <li>
                        <a href="reorder-level.php"><i class="fa fa-fw fa-desktop"></i> Reorder Level <span class="label label-default"><?php
                            echo mysqli_num_rows($resultReorderProducts);
                        ?></span></a>
                    </li>
                    <li>
                        <a href="inventory.php"><i class="fa fa-fw fa-wrench"></i> Inventory</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Sales <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="#">Delivery</a>
                            </li>
                            <li>
                                <a href="#">Payments</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>