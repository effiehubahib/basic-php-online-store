<?php
require('../lib/fpdf181/fpdf.php');
include_once("admin_config.php");

$cart_id = $_GET['id'];

	$query = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE id_cart='$cart_id'";
	$result = mysqli_query($conn,$query);

$queryOrder = "SELECT tp.product_name as product_name, cp.price as price, cp.refunded as refunded, cp.refund_amount as refund_amount, cp.quantity as quantity,tc.categoryname as categoryname FROM tbl_cart_product cp 
          LEFT JOIN tbl_product tp ON cp.id_product = tp.id_product 
          LEFT JOIN tbl_category tc ON tp.id_category = tc.id_category 
          WHERE id_cart='".$cart_id."'";
      		
$resultOrder = mysqli_query($conn,$queryOrder);
$delivery_fee = 0;
$referenceno = '';
if(mysqli_num_rows($result)>0){
	while($cartInfo=mysqli_fetch_array($result)){
		$dateadded= $cartInfo['dateadded'];
		$delivery_fee = $cartInfo['delivery_fee'];
		$referenceno = $cartInfo['referenceno'];
		$firstname = $cartInfo['firstname'];
	 $queryBalance = "SELECT * FROM amountpaid WHERE cart_id='".$cartInfo['id_cart']."' && user_id='".$cartInfo['id_user']."'";
      $resultBalance = mysqli_query($conn,$queryBalance);
  }
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(70);
$pdf->Cell(0,20,'SBQ Furniture');
$pdf->Ln(6);
$pdf->SetFont('Arial','',10);
$pdf->Cell(58);
$pdf->Cell(0,20,'123 Avocado Street, Talisay City 6045');
$pdf->Ln(4);
$pdf->Cell(70);
$pdf->Cell(0,20,'Telephone: 123-4567');
$pdf->Ln(12);
$pdf->Cell(56);
$pdf->SetTextColor(255,0,0);
$pdf->SetFont('Arial','',16);
$pdf->Cell(0,20,'Receipt : '.date("Ymd",strtotime($dateadded))."-".$cart_id );
$pdf->Ln(22);
$pdf->Cell(56);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',11);

$pdf->Ln(12);
$pdf->SetFont('Arial','',11);
$pdf->Cell(1,0,"Order Reference #: ".$referenceno );
$pdf->Cell(120);
$pdf->Cell(1,0,"Order Date: ".date("F d, Y",strtotime($dateadded)) );
$pdf->Ln(7);
$pdf->Cell(1,0,"Customer: ".$firstname);
$pdf->Ln(12);
    
if(mysqli_num_rows($resultOrder)>0){
	$header = array('Product Name', 'Category', 'Quantity', 'Unit Price', 'Total');
 	foreach($header as $col){
	 	if($col == 'Product Name')
	 	{
	 		$pdf->Cell(60,7,$col,1);
	 	}elseif($col == 'Quantity'){
	 		$pdf->Cell(20,7,$col,1);
	 	}elseif($col == 'Total'){
	 		$pdf->Cell(30,7,$col,1);
	 	}else{
		    $pdf->Cell(40,7,$col,1);
	    }
    }

		    $pdf->Ln();
			$ctr = 0;
			$overallTotal = 0;
			while($product=mysqli_fetch_array($resultOrder))
			{
				$productTotal = $product['price']*$product['quantity'];
				$overallTotal += $productTotal;
			    $pdf->Cell(60,6,$product['product_name'],1);
			     $pdf->Cell(40,6,$product['categoryname'],1);
			    $pdf->Cell(20,6,$product['quantity'],1);
			    $pdf->Cell(40,6,number_format($product['price'],2),1);
			    $pdf->Cell(30,6,number_format($productTotal,2),1);
			    $pdf->Ln();
			    
			}
			if($delivery_fee){
                
                $pdf->Cell(60,6,"Delivery Fee",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(20,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(30,6,number_format($delivery_fee,2),1,0);
			    $pdf->Ln();
                 $productTotal+= ($delivery_fee);
            }
			 $pdf->Cell(60,6,"Overall Amount",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(20,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(30,6,number_format($productTotal,2),1);
			    $pdf->Ln();

		 if(mysqli_num_rows($resultBalance)>0)
	        {
	          $totalbalance =0;
	            while($balance=mysqli_fetch_array($resultBalance)){
	                  
	                $pdf->Cell(60,6,"Paid (".date("M d, Y", strtotime($balance['date_created'])).")",1);
				    $pdf->Cell(40,6,"",1);
				    $pdf->Cell(20,6,"",1);
				    $pdf->Cell(40,6,"",1);
				    $pdf->Cell(30,6," - ".number_format($balance['amount'],2),1);
				    $pdf->Ln();
				    $totalbalance += $balance['amount'];
	                }
	                
	            $pdf->Cell(60,6,"Balance",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(20,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(30,6,number_format($overallTotal - $totalbalance,2),1);
			    $pdf->Ln();

	        }

        
                
}

/* Refund table*/
$queryOrder2 = "SELECT tp.product_name as product_name, cp.price as price, cp.refunded as refunded, cp.refund_amount as refund_amount, cp.quantity as quantity,tc.categoryname as categoryname FROM tbl_cart_product cp 
          LEFT JOIN tbl_product tp ON cp.id_product = tp.id_product 
          LEFT JOIN tbl_category tc ON tp.id_category = tc.id_category 
          WHERE id_cart='".$cart_id."' && refunded=1";

$resultOrder2 = mysqli_query($conn,$queryOrder2);

if(mysqli_num_rows($resultOrder2)>0){
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(1,6,"Refunded:");
	$pdf->Ln();
	$header = array('Product Name', 'Deduction');
 	foreach($header as $col){
	 	if($col == 'Product Name')
	 	{
	 		$pdf->Cell(100,6,$col,1);
	 	}else{
	 		$pdf->Cell(40,6,$col,1);
	 	}
	    
    }

		    $pdf->Ln();
			$ctr = 0;
			$overallTotal = 0;
			while($prod=mysqli_fetch_array($resultOrder2))
			{
				
			    $pdf->Cell(100,6,$prod['product_name'],1);
			     $pdf->Cell(40,6,$prod['refund_amount'],1);
			    $pdf->Ln();
			    
			}
}
$pdf->Ln(12);
$pdf->SetFont('Arial','',10);
$pdf->Cell(1,0,"Valid until: ");
$pdf->Ln(8);
$pdf->Cell(1,0,"Printed by: ".$_SESSION["user_name"] );
$pdf->Ln(12);
$pdf->SetFont('Arial','',8);
$pdf->Cell(1,0,"Receipt Generated Date: ".date("M d, Y h:i a") );
$pdf->Ln(12);
$pdf->Output();

?>
