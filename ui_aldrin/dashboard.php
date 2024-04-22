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
                                <h2 class="text-white" >Weekly Sales Report</h2>
                                <canvas id="inventory-chart"></canvas>
                            </div>
                        </div>

                        <!-- Stocks Alert -->
                        <div class="col-sm-6 d-flex">
                            <div class="col-md bg-dark p-3 rounded flex-grow-1">
                                <h2 class="text-white" >Stocks Alert</h2>
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Dark Choco</td>
                                            <td>450 g</td>
                                            <td>03/31/24</td>
                                        </tr>
                                        <tr>
                                            <td>Dark Choco</td>
                                            <td>450 g</td>
                                            <td>03/31/24</td>
                                        </tr>
                                        <tr>
                                            <td>Dark Choco</td>
                                            <td>450 g</td>
                                            <td>03/31/24</td>
                                        </tr>
                                        <tr>
                                            <td>Dark Choco</td>
                                            <td>450 g</td>
                                            <td>03/31/24</td>
                                        </tr>
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
                                    <th>Receipt No.</th>
                                    <th>Qty</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>103</td>
                                    <td>3</td>
                                    <td>Dark Choco</td>
                                    <td>120</td>
                                    <td>240</td>
                                    <td><button type="submit" class="btn-danger">edit</button></td>
                                </tr> 
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

<script>
    const inventoryData = {
        labels: ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
        datasets: [{
            label: 'Sales Report',
            data: [100, 75, 50, 25, 10],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Chart configuration
    const config = {
        type: 'bar',
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