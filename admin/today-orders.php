<?php
require('../lib/fpdf181/fpdf.php');
include_once("admin_config.php");
$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");


if(!isset($_GET['day'])){
	$queryToday = "SELECT *,tbl_user.id as user_id FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE DATE(tbl_cart.dateadded) = DATE(NOW())";
}else{

	$queryToday = "SELECT *,tbl_user.id as user_id FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE DATE(tbl_cart.dateadded) = DATE('".$_GET['day']."')";
}

$resultToday = mysqli_query($conn,$queryToday);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(70);
$pdf->Cell(0,20,'SBQ Furniture');
$pdf->Ln(22);
$pdf->Cell(60);
$pdf->SetFont('Arial','',12);
$pdf->Cell(1,0,"Today's Order : ".date("F d, Y") );
$pdf->Ln(12);

$pdf->Ln(12);
$pdf->Cell(50,6,"Customer's ID",0);
$pdf->Cell(60,6,"Customer's Name",0);
$pdf->Cell(60,6,"Delivery Status",0);
$pdf->Ln();
if(mysqli_num_rows($resultToday)>0){
			
			while($cartToday=mysqli_fetch_array($resultToday))
			{
				$pdf->Cell(50,6,$cartToday['user_id'],0);
				$pdf->Cell(60,6,$cartToday['lastname'].", ".$cartToday['firstname'],0);
			    $pdf->Cell(60,6,$deliverystatus[$cartToday['delivery_status']],0);
			    $pdf->Ln();
			}
		?>

<?php
}else{
	$pdf->Cell(1,0,"No order!");
}

$pdf->Output();

?>
