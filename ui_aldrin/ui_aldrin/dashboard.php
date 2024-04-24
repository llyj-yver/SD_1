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
        <div id="dashboard" class="article flex-grow-1">
            <h1>Dashboard</h1>
            <div class="row g-2">
                <div class="container mt-5">
                    <div class="row">
                        <!-- Sales Report -->
                        <div class="col-sm-6 d-flex ">
                            <div class="col-md bg-dark p-3 rounded flex-grow-1 mr-sm-3">
                                <h2 class="text-white">Weekly Sales Report</h2>
                                <canvas id="inventory-chart"></canvas>
                            </div>
                        </div>

                        <!-- Stocks Alert -->
                        <div class="col-sm-6 d-flex">
                            <div class="col-md bg-dark p-3 rounded flex-grow-1">
                                <h2 class="text-white">Stocks Alert</h2>
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include "db_config.php";

                                        // Fetch data from the database and display in the table
                                        $sql = "SELECT * FROM `inventory`";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                if ($row['quantity'] < 400) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['Id'] . "</td>";
                                                    echo "<td>" . $row['product_name'] . "</td>";
                                                    echo "<td>" . $row['quantity'] . "g</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No products found that need to restock</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Sales</th>
                                    <th>Amount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "db_config.php";

                                $sql = "SELECT DATE(date) as day, SUM(sales) as total_sales FROM sales GROUP BY DATE(date) ORDER BY DATE(date) DESC";
                                $result = $conn->query($sql);

                                $id_x = 1;
                                $mean_x = 0;// Initialize id counter
                                $mean_y = 0;
                                $mean_x2 = 0;
                                $mean_xy = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $id_x . "</td>"; // Display id
                                        echo "<td>" . date('l, d F Y', strtotime($row['day'])) . "</td>";
                                        echo "<td>" . $row['total_sales'] . "</td>";
                                        echo "<td>" . $id_x ** 2 . "</td>";
                                        echo "<td>" . $row['total_sales'] * $id_x . "</td>";
                                        echo "</tr>";


                                        $id_x++; // Increment id counter
                                        $mean_x = $mean_x + $id_x;
                                        $mean_y = $mean_y + $row['total_sales'];
                                        $mean_x2 = $mean_x2 + ($id_x ** 2);
                                        $mean_xy = $mean_xy + ($row['total_sales'] * $id_x);
                                        $day[] = $id_x;
                                        $sales[] = $row['total_sales'];
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No data found</td></tr>";
                                } ?>
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

$days_sales = [
    "days" => [],
    "sales" => [],
];

$sql = "SELECT DATE(date) as day, SUM(sales) as total_sales FROM sales GROUP BY DATE(date) ORDER BY DATE(date) DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $days = []; // Initialize days array
    $sales = []; // Initialize sales array

    while ($row = $result->fetch_assoc()) {
        // Store data in arrays
        $days[] = date('l, d F Y', strtotime($row['day']));
        $sales[] = $row['total_sales'];
    }

    // Reverse the arrays
    $days = array_reverse($days);
    $sales = array_reverse($sales);

    // Assign reversed arrays to days_sales
    $days_sales["days"] = $days;
    $days_sales["sales"] = $sales;
}

?>

<script>
    const inventoryData = {
        labels: <?php echo json_encode($days_sales["days"]); ?>,
        datasets: [{
            label: 'Sales Report',
            data: <?php echo json_encode($days_sales["sales"]); ?>,
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
            ],
            borderWidth: 1
        }]
    };

    // Chart configuration
    const config = {
        type: 'line',
        data: inventoryData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };

    // Initialize the chart
    var myChart = new Chart(
        document.getElementById('inventory-chart'),
        config
    );
</script>