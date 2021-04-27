<?php
require('lib/fpdf181/fpdf.php');
include_once("config.php");
$cart_id = $_GET['id'];

$query = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE id_cart='$cart_id'";
$result = mysqli_query($conn,$query);

$queryOrder = "SELECT tp.product_name as product_name, cp.price as price, cp.quantity as quantity,tc.categoryname as 		  categoryname FROM tbl_cart_product cp 
          LEFT JOIN tbl_product tp ON cp.id_product = tp.id_product 
          LEFT JOIN tbl_category tc ON tp.id_category = tc.id_category 
          WHERE id_cart='".$cart_id."'";
      		
$resultOrder = mysqli_query($conn,$queryOrder);
$datecreated = date("Ymd");
$reference ='';
if(mysqli_num_rows($result)>0){
	while($cartInfo=mysqli_fetch_array($result)){
	 $queryBalance = "SELECT amountpaid.*, tbl_user.firstname as firstname, tbl_user.lastname as lastname FROM amountpaid LEFT JOIN tbl_user ON amountpaid.receivedby = tbl_user.id WHERE cart_id='".$cartInfo['id_cart']."' && user_id='".$cartInfo['id_user']."'";
      $resultBalance = mysqli_query($conn,$queryBalance);
      $resultPaid = mysqli_query($conn,$queryBalance);
      $datecreated = $cartInfo['dateadded'];
      $reference = $cartInfo['referenceno'];
      $delivery_fee = $cartInfo['delivery_fee'];
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
$pdf->Cell(0,20,'Receipt : '.date("Ymd",strtotime($datecreated))."-".$cart_id );
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',11);
$pdf->Ln(12);

$pdf->Ln(12);
$pdf->Cell(1,0,"Reference #: ".$reference );
$pdf->Cell(120);
$pdf->Cell(1,0,"Order Date: ".date("F d, Y") );
$pdf->Ln(12);    
if(mysqli_num_rows($resultOrder)>0){
	$header = array('Product Name', 'Category', 'Quantity', 'Unit Price', 'Total');
 	foreach($header as $col)
 		if($col!='Quantity')
    		$pdf->Cell(40,7,$col,1);
    	else
    		$pdf->Cell(20,7,$col,1);
    $pdf->Ln();
    
			$ctr = 0;
			$overallTotal = 0;
			while($product=mysqli_fetch_array($resultOrder))
			{
				$productTotal = $product['price']*$product['quantity'];
				$overallTotal += $productTotal;
			    $pdf->Cell(40,6,$product['product_name'],1);
			     $pdf->Cell(40,6,$product['categoryname'],1);
			    $pdf->Cell(20,6,$product['quantity'],1);
			    $pdf->Cell(40,6,number_format($product['price'],2),1);
			    $pdf->Cell(40,6,number_format($productTotal,2),1,0,0);
			    $pdf->Ln();
			    
			}	
			if($delivery_fee){
                
                $pdf->Cell(40,6,"Delivery Fee",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(20,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6,number_format($delivery_fee,2),1,0,0);
			    $pdf->Ln();
                 $productTotal+= ($delivery_fee);
            }
			 $pdf->Cell(40,6,"Overall Amount",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(20,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6,number_format($productTotal,2),1,0,0);
			    $pdf->Ln();
		 if(mysqli_num_rows($resultBalance)>0)
        {
          $totalbalance =0;
            while($balance=mysqli_fetch_array($resultBalance)){
                  
                $pdf->Cell(40,6,"Paid (".date("M d, Y", strtotime($balance['date_created'])).")",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(20,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6," - ".number_format($balance['amount'],2),1);
			    $pdf->Ln();
			    $totalbalance += $balance['amount'];
                }
              
            $pdf->Cell(40,6,"Balance",1);
		    $pdf->Cell(40,6,"",1);
		    $pdf->Cell(20,6,"",1);
		    $pdf->Cell(40,6,"",1);
		    $pdf->Cell(40,6,number_format($overallTotal - $totalbalance,2),1,0,0);
		    $pdf->Ln();

        }

        
                
}
$pdf->Ln(12);
$pdf->SetFont('Arial','',8);
$pdf->Cell(1,0,"Receipt Generated Date: ".date("M d, Y h:i a") );
$pdf->Ln(12);

while($paid=mysqli_fetch_array($resultPaid)){          
	$pdf->Cell(40,6," Paid Amount(".date("M d, Y", strtotime($paid['date_created'])).") received by ".$paid['firstname']." ".$paid['lastname']);
	$pdf->Ln();
}  

$pdf->Output();

?>
