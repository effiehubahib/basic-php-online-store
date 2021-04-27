<?php
require('../lib/fpdf181/fpdf.php');
include_once("admin_config.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");

date_default_timezone_set(date_default_timezone_get());


if(!isset($_GET['year'])){
	$currdate = date("Y");
	$queryYearOrder = "SELECT *,tbl_user.id as user_id FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE YEAR(tbl_cart.dateadded) = YEAR(NOW())";
}else{
	$currdate = $_GET['year'];
	$queryYearOrder = "SELECT *,tbl_user.id as user_id FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE YEAR(tbl_cart.dateadded) = '".$_GET['year']."'";
}


$resultYearOrder = mysqli_query($conn,$queryYearOrder);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(70);
$pdf->Cell(0,20,'SBQ Furniture');
$pdf->Ln(22);
$pdf->Cell(60);
$pdf->SetFont('Arial','',12);
$pdf->Cell(1,0,"Order of Year: ".$currdate);
$pdf->Ln(12);
$pdf->Cell(50,6,"Customer's ID",0);
$pdf->Cell(60,6,"Customer's Name",0);
$pdf->Cell(60,6,"Delivery Status",0);
$pdf->Ln();
if(mysqli_num_rows($resultYearOrder)>0){
			
			while($cartYear=mysqli_fetch_array($resultYearOrder))
			{
				$pdf->Cell(50,6,$cartYear['user_id'],0);
				$pdf->Cell(60,6,$cartYear['lastname'].", ".$cartYear['firstname'],0);
			    $pdf->Cell(60,6,$deliverystatus[$cartYear['delivery_status']],0);
			    $pdf->Ln();
			}
		?>

<?php
}else{
	$pdf->Cell(1,0,"No orders!");
}

$pdf->Output();

?>
