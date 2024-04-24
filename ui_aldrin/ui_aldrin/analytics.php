<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper">
        <!--start of sidebar-->
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">Anne Tea</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="dashboard.php" onclick="showContent('dashboard')" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="pos.php" onclick="showContent('pos')" class="sidebar-link">
                        <i class="lni lni-agenda"></i>
                        <span>Point Of Sale</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="inventory.php" onclick="showContent('inventory')" class="sidebar-link">
                        <i class="lni lni-agenda"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="analytics.php" onclick="showContent('analytics')" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="products.php" onclick="showContent('products')" class="sidebar-link">
                        <i class="lni lni-cog"></i>
                        <span>Products</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <!--End of sidebar-->

        <!--Main-->
        <div id="analytics" class="article flex-grow-1">
            <h1>Analytics</h1>
            <div class="container ">
                <div class="row g-2">
                    <!-- Weekly Sales Report -->
                    <div class="col-sm-6 d-flex">
                        <div class=" bg-dark rounded-3 flex-grow-1 ">
                            <h4 class="mx-2 text-light">Sales Report</h4>
                            <canvas id="weeklySalesChart"></canvas>
                        </div>
                    </div>

                    <!-- Sales Forecast -->
                    <div class="col-sm-6 d-flex">
                        <div class=" bg-dark   rounded-3 flex-grow-1">
                            <h4 class="mx-2 text-light">Sales Forecast</h4>
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div class="rounded-3" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Receipt No.</th>
                                    <th>Date</th>
                                    <th>Qty</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Size</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "db_config.php";

                                // Fetch data from the database in descending order
                                $sql = "SELECT * FROM `sales_analytics` ORDER BY Id DESC";
                                $result = $conn->query($sql);
                        
                                if ($result->num_rows > 0) {
                                    $sum_total = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['Id'] . "</td>";
                        
                                        // Convert date to month name format
                                        $date = date('F d, h:i A', strtotime($row['date']));
                                        echo "<td>" . $date . "</td>";
                        
                                        echo "<td>" . $row['quantity'] . "</td>";
                                        echo "<td>" . $row['product_name'] . "</td>";
                                        echo "<td>" . $row['price'] . "</td>";
                                        echo "<td>" . $row['size'] . "</td>";
                                        echo "<td>" . $row['total'] . "</td>";
                                        echo "</tr>";
                                        $sum_total = $sum_total + $row['total'];
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No products found</td></tr>";
                                }
                        
                                ?>
                                <!-- Repeat rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="script.js"></script>
</body>

</html>
<?php
include "db_config.php";

// Fetching data for sales
$sql = "SELECT date, sales FROM sales";
$result = $conn->query($sql);

$Date = [];
$Sales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Store data in arrays 
        $Date[] = date('F d, h:i A', strtotime($row['date']));
        $Sales[] = $row['sales'];
    }
}
include "forecast.php";
?>

<script>
    // Weekly Sales Report Data
    const weeklySalesData = {
        labels: <?php echo json_encode($Date); ?>,
        datasets: [{
            label: 'Sales Report',
            data: <?php echo json_encode($Sales); ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };

    // Weekly Sales Report Configuration
    const weeklySalesConfig = {
        type: 'bar',
        data: weeklySalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };

    // Sales Forecast Data
    const salesForecastData = {
        labels: <?php echo json_encode($sales_forecast["date"]); ?>,
        datasets: [{
            label: 'Sales Forecast',
            data: <?php echo json_encode($sales_forecast["sales"]); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    // Sales Forecast Configuration
    const salesForecastConfig = {
        type: 'line',
        data: salesForecastData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };

    // Initialize the charts
    var weeklySalesChart = new Chart(
        document.getElementById('weeklySalesChart'),
        weeklySalesConfig
    );

    var salesChart = new Chart(
        document.getElementById('salesChart'),
        salesForecastConfig
    );
</script>