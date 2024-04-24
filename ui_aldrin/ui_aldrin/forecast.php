<?php
include "db_config.php";

$sql = "SELECT DATE(date) as day, SUM(sales) as total_sales FROM sales GROUP BY DATE(date) ORDER BY DATE(date) DESC";
$result = $conn->query($sql);

$sales_forecast = [
    "x" => [],
    "date" => [],
    "sales" => [],
];

$id = 0;

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $day = date('l, d F Y', strtotime($row['day']));
        $sale = $row['total_sales'];
        $id += 1;

        $sales_forecast["x"][] = $id;
        $sales_forecast["date"][] = $day;
        $sales_forecast["sales"][] = $sale;
    }
    
    $sales_forecast["date"] = array_reverse($sales_forecast["date"]);
    $sales_forecast["sales"] = array_reverse($sales_forecast["sales"]);
}

$sumX = array_sum($sales_forecast['x']);
$sumY = array_sum($sales_forecast['sales']);
$sumXY = 0;
$sumXSquare = 0;

for ($i = 0; $i < count($sales_forecast['x']); $i++) {
    $sumXY += $sales_forecast['x'][$i] * $sales_forecast['sales'][$i];
    $sumXSquare += $sales_forecast['x'][$i] ** 2;
}

// Calculate the numerator and denominator for the slope (a1)
$numerator = (count($sales_forecast['x']) * $sumXY) - ($sumX * $sumY);
$denominator = (count($sales_forecast['x']) * $sumXSquare) - ($sumX ** 2);

// Check for division by zero
if ($denominator == 0) {
    die("Error: Division by zero.");
}

// Calculate the slope (a1) and intercept (b0)
$a1 = $numerator / $denominator;
$b0 = ($sumY - ($a1 * $sumX)) / count($sales_forecast['x']);

//forecast values
$xy_array = [
    "x" => [],
    "y" => [],
];
$forecast_days = count($sales_forecast['x']) + 1;
while($forecast_days != count($sales_forecast['x']) + 4){
    $y = $b0 + $a1 * $forecast_days;
    $xy_array['x'][] = "day". $forecast_days;
    $xy_array['y'][] = $y;
    $forecast_days+=1;
}


$sales_forecast['date'] = array_merge($sales_forecast['date'],$xy_array['x']);
$sales_forecast['sales'] = array_merge($sales_forecast['sales'],$xy_array['y']);

