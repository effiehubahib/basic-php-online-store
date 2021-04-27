<?php
include_once("config.php");
session_start();
?>
<!DOCTYPE HTML>

<html lang="en-us">
<head>
	<meta charset="utf-8" />
	<title>Arome Tires Company</title>
		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext" type="text/css" media="all" />
		<link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
		<link rel="stylesheet" href="css/blockcontact.css" type="text/css" media="all" />
		<link rel="stylesheet" href="font-awesome-4.6.3/css/font-awesome.min.css" type="text/css" media="all" />
		 <!-- Bootstrap Core CSS -->
	    <link href="assets/AdminThemes/css/bootstrap.min.css" rel="stylesheet">

	    <!-- Custom CSS -->
	    <link href="assets/AdminThemes/css/sb-admin.css" rel="stylesheet">

	    <!-- Morris Charts CSS -->
	    <link href="assets/AdminThemes/css/plugins/morris.css" rel="stylesheet">

	    <!-- Custom Fonts -->
	    <link href="assets/AdminThemes/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="lib/lightbox2/dist/css/lightbox.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
		<link rel="stylesheet" type="text/css" href="css/datatables.min.css" />
		<script type="text/javascript" src="scripts/jquery-3.1.0.min.js"></script>
		<script src="lib/lightbox2/dist/js/lightbox.js"></script>
		<script type="text/javascript" src="scripts/product.js"></script>
		<script type="text/javascript" src="scripts/accounting.js"></script>
		<script type="text/javascript" src="js/validator.js"></script>

</head>
<body>
	<div class="header-container">
		<header id="header">
			
			<div class="banner">
				<div class="container">
				<div class="row">
					<div id="header_logo">
						<a href="index.php" title="Presta Store">
							<img class="logo img-responsive" src="images/aptslogo.png" width="150px" height="150px" >
						</a>
					</div>
					
				</div>
				</div>
			</div>
			<div class="nav">
				<div class="container">
						<div class="row">
							<nav>
								<div class="header_user_info">
								<div id='navigation-link'><a class='home' href='index.php' rel='nofollow' title='Home'>Home</a></div>
								<div id="navigation-link">
									<a href="contact-us.php" title="Contact us">Contact us</a>
								</div>
								<div id="navigation-link">
									<a href="brands.php">Brands</a>
								</div>
							  <?php if(!isset($_SESSION["user_id"]))
									{ 
									echo"<div id='navigation-link'><a class='login' href='login.php' rel='nofollow' title='Log in to your account'>Sign in</a></div>
										<div id='navigation-link'><a href='register.php' title='Contact us'>Register</a></div>
									";
									}else{
										$queryUser = "SELECT * FROM tbl_user WHERE user_id='".$_SESSION['user_id']."'";
										$resultUser = mysqli_query($conn,$queryUser);
								
										if($resultUser->num_rows>0)
										{	
											while($userLoggedIn=mysqli_fetch_array($resultUser))
											{	
												
											
												if($userLoggedIn['user_type']=="Customer"){
													echo"<div id='navigation-link'><a class='dashboard' href='customer-dashboard.php'>My Orders</a></div>";	
													
												}else{
													echo"<div id='navigation-link'><a class='dashboard' href='admin/index.php'>Dashboard</a></div>";
												}	
												echo "<div id='navigation-link'><a href='profile.php' title='Profile'> Profile</a></div>";	
												echo "<div id='navigation-link'><a class='logout' href='logout.php' rel='nofollow' title='Log out your account'>Logout </a></div>";
											}
										}
									
								}
								?>
									
							
									<a href="cart.php">
									<div class="top-cart pull-right">
										<p>	<i class="fa fa-shopping-cart" aria-hidden="true"></i> 
						                        <?php
						                        // count products in cart
						                        $cookie =  isset($_COOKIE['cart_items_cookie']) ?$_COOKIE['cart_items_cookie']: "";
						                        $cookie = stripslashes($cookie);
						                        $saved_cart_items = json_decode($cookie, true);
						                        $cart_count=count($saved_cart_items);
						                        ?>
						                        <span class="badge" id="comparison-count"><?php echo $cart_count; ?></span>
						                   
										</p>
									</div>
									 </a>
								</div>
								
								
								
							</nav>
					</div>
				</div>
			</div>
		</header>
	</div>