<?php

include_once("admin-config.php");
include_once("admin-header.php");
$month = date("m");
$currentYr = date("Y");

$addedStocksData = array();
$monthlyStockAddedForecast = array();
$stockDataFormat ='';
$stockDataForecastFormat = '';

$queryStatAddedStock = "SELECT SUM(ta.stockquantity * tp.price) as totalstock, MONTH(ta.date_added) as stockmonth,YEAR(ta.date_added) as stockyear FROM tbl_addedstock ta LEFT JOIN tbl_product tp ON ta.product_id = tp.product_id WHERE YEAR(ta.date_added) = YEAR(NOW()) GROUP BY stockmonth, stockyear";

$resultStatAddedStock = mysqli_query($conn,$queryStatAddedStock);
$result = mysqli_query($conn,$queryStatAddedStock);
if(mysqli_num_rows($resultStatAddedStock)>0)
{
    while($row=mysqli_fetch_array($resultStatAddedStock))
    {
        $addedStocksData[$row['stockmonth']] = $row['totalstock'];
    }
}
//monthly added stocks current year



/* for added stocks */
for($x=1;$x<=12;$x++){
    if(array_key_exists($x,$addedStocksData))
    {
        $value = $addedStocksData[$x];
    }else{
        $value = 0;
    }
     $stockDataFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                addedstocks: '.$value.'
            },';

    if($x<=$month){
        $monthlyStockAddedForecast[$x] = $value;
            $stockDataForecastFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                addedstocks: '.$value.'
            },';
    }else{

        //echo"B1:".$monthlyStockAddedForecast[$x-2];
       // echo"<br/>B2:".$monthlyStockAddedForecast[$x-1];
        $monthlyStockAddedForecast[$x] = ($monthlyStockAddedForecast[$x-2] + $monthlyStockAddedForecast[$x-1])/2;
        //echo "<br/>B3:".$monthlyStockAddedForecast[$x];
        $stockDataForecastFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                addedstocks: "'.number_format($monthlyStockAddedForecast[$x],2,'.','').'"
            },';
    }

}
?>

 <div id="page-wrapper">

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Added Stocks</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->
        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Month</th>
                  <th>Year</th>
                  <th>Total Added Stocks</th>
                </tr>
            </thead>
            <tbody>
             <?php 

              while($stock=mysqli_fetch_array($result))
              {
                
                 echo "<tr>";
                  echo "<td>".date("M",strtotime($stock['stockyear']."-".$stock['stockmonth']."-01"))."</td>";
                  echo "<td>".$stock['stockyear']."</td>";
                  echo "<td>".$stock['totalstock']."</td>";
                 echo "</tr>";
              }
             ?> 
             </tbody>
              <tfoot>
            </tfoot>
        </table>
        <div class="col-lg-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Added Stocks <?php echo date("Y");?></h3>
                </div>
                <div class="panel-body">
                    <div id="morris-added-stocks"></div>
                   
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Added Stocks Forecast <?php echo date("Y");?></h3>
                </div>
                <div class="panel-body">
                    <div id="morris-added-stocks-forecast"></div>
                   
                </div>
            </div>
        </div>


        
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>


<?php
include_once("admin-footer.php");
?>
<script type="text/javascript">
    // Morris.js Charts sample data for SB Admin template
$( document ).ready(function() {
    $(function() {

        
       // Bar Chart
        Morris.Bar({
            element: 'morris-added-stocks',
            data: [<?php echo $stockDataFormat;?>],
            xkey: 'month',
            ykeys: ['addedstocks'],
            labels: ['Total Added Stocks'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });

        Morris.Bar({
            element: 'morris-added-stocks-forecast',
            data: [<?php echo $stockDataForecastFormat;?>],
            xkey: 'month',
            ykeys: ['addedstocks'],
            labels: ['Forecast Total Stocks'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });
    });
});

</script>