<?php
include "db_config.php";

$product_sizes = [];
$Pa_name = ''; // Variable to store product name
$Sa_name = '';   // Variable to store quantity

if (isset($_POST['submit_items'])) {
    $P_name = $_POST['product_name'];
    $size_price = $_POST['size_price'];
    $quantity = $_POST['quantity'];
    $total = $quantity * intval($size_price);

    $sql_sizes = "SELECT * FROM `sizes_menu` WHERE `price`= '$size_price'";
    $result123 = $conn->query($sql_sizes);
    if ($result123->num_rows > 0) {
        while ($row = $result123->fetch_assoc()) {
            $S_name = $row['size'];
        }
    }

    // Prepare and execute the SQL statement to insert new inventory
    $sql = "INSERT INTO `cart`(`product_name`, `price`, `size`, `quantity`, `total`) VALUES ('$P_name', '$size_price', '$S_name', '$quantity', '$total')";
    $result = $conn->query($sql);
    $Pa_name = $P_name;
    $Sa_name = $S_name;
}
//place Order
if (isset($_POST['place_items'])) {
    $total = $_POST['total_bill'];
    //insert the cart into sales analytics
    $sql_cart_sa = "SELECT * FROM cart";
    $result = $conn->query($sql_cart_sa); // Use a different variable name

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name_p = $row['product_name'];
            $prc = $row['price'];
            $sz = $row['size'];
            $qty_p = $row['quantity'];
            $ttl = $row['total'];

            $sql = "INSERT INTO `sales_analytics` (`product_name`, `price`, `size`, `quantity`,`total`) VALUES ('$name_p','$prc','$sz','$qty_p','$ttl')";
            $resulttr = $conn->query($sql);
        }
    }
    // insert sales
    $sql_input = "INSERT INTO `sales`(`sales`) VALUES ('$total')";
    $result = $conn->query($sql_input);
    //subtracting the used goods in inv
    $sql_get_sub = "SELECT * FROM `cart`";
    $result_get_sub = $conn->query($sql_get_sub);
    if ($result_get_sub->num_rows > 0) {
        while ($row = $result_get_sub->fetch_assoc()) {
            $P_name = $row['product_name'];
            $price = $row['price'];
            $qty_cart = $row['quantity'];

            $sql_sizes1 = "SELECT * FROM `sizes_menu` WHERE `price`= '$price'";
            $result90 = $conn->query($sql_sizes1);
            $pearl_content_inv = 0;
            $tea_content_inv = 0;
            $flavor_content_inv = 0;
            $sugar_content_inv = 0;

            if ($result90->num_rows > 0) {
                while ($row = $result90->fetch_assoc()) {
                    $pearl_content_inv = $row['pearl_content'] * $qty_cart;
                    $tea_content_inv = $row['tea_content'] * $qty_cart;
                    $flavor_content_inv = $row['flavor_content'] * $qty_cart;
                    $sugar_content_inv = $row['sugar_content'] * $qty_cart;
                }
            }

            $sql = "SELECT
                    SUM(CASE WHEN product_name = 'pearl' THEN quantity ELSE 0 END) AS quantity_pearl,
                    SUM(CASE WHEN product_name = 'tea' THEN quantity ELSE 0 END) AS quantity_tea,
                    SUM(CASE WHEN product_name = '$P_name' THEN quantity ELSE 0 END) AS quantity_powder,
                    SUM(CASE WHEN product_name = 'sugar' THEN quantity ELSE 0 END) AS quantity_sugar
                    FROM
                    inventory
                    WHERE
                    product_name IN ('pearl', 'tea', '$P_name', 'sugar');";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of the first (and only) row
                $row = $result->fetch_assoc();

                $quantity_pearl = $row['quantity_pearl'];
                $quantity_tea = $row['quantity_tea'];
                $quantity_flavor = $row['quantity_powder'];
                $quantity_sugar = $row['quantity_sugar'];
            }


            $updt_P = $quantity_pearl - $pearl_content_inv;
            $updt_T = $quantity_tea - $tea_content_inv;
            $updt_F = $quantity_flavor - $flavor_content_inv;
            $updt_S = $quantity_sugar - $sugar_content_inv;

            $newQuantities = [
                'pearl' => $updt_P,
                'tea' => $updt_T,
                $P_name => $updt_F,
                'sugar' => $updt_S
            ];

            // Update quantities for products
            foreach ($newQuantities as $product => $quantity) {
                $sql = <<<SQL
                        UPDATE inventory
                        SET quantity = 
                    CASE
                        WHEN product_name = '$product' THEN $quantity
                        ELSE quantity
                        END
                    WHERE product_name = '$product';
                    SQL;

                if ($conn->query($sql) === TRUE) {

                } else {

                }
            }

            $sql = "SELECT
            SUM(CASE WHEN product_name = 'pearl' THEN quantity ELSE 0 END) AS quantity_pearl,
            SUM(CASE WHEN product_name = 'tea' THEN quantity ELSE 0 END) AS quantity_tea,
            SUM(CASE WHEN product_name = '$P_name' THEN quantity ELSE 0 END) AS quantity_powder,
            SUM(CASE WHEN product_name = 'sugar' THEN quantity ELSE 0 END) AS quantity_sugar
            FROM
            inventory
            WHERE
            product_name IN ('pearl', 'tea', '$P_name', 'sugar');";

            $result = $conn->query($sql);


            if ($result->num_rows > 0) {
                // Output data of the first (and only) row
                $row = $result->fetch_assoc();

                $quantity_pearl = $row['quantity_pearl'];
                $quantity_tea = $row['quantity_tea'];
                $quantity_flavor = $row['quantity_powder'];
                $quantity_sugar = $row['quantity_sugar'];
            }
        }
    }




    // SQL query to delete all rows from the table
    $sql_clear = "DELETE FROM cart";
    $result = $conn->query($sql_clear);



}

// Clear the orders table
if (isset($_POST['clear_items'])) {
    // SQL query to delete all rows from the table
    $sql = "DELETE FROM cart";
    $result = $conn->query($sql);
}
?>
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
        <div id="pos" class="article flex-grow-1">
            <h1>Point of Sale</h1>
            <div class="row g-2">
                <div class="container mt-5">
                    <div class="row">
                        <!-- Menu -->
                        <div class="col-sm-6 d-flex">
                            <div class="col-md bg-dark p-3 overflow-auto rounded flex-grow-1 mr-sm-3 vh-100">
                                <h2 class="text-white">Menu</h2>
                                <div class="container">
                                    <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
                                        <!-- Items -->
                                        <?php
                                        include "db_config.php";

                                        // Query to retrieve Product_name from products_menu table
                                        $sql_products = "SELECT Product_name, Id FROM products_menu";
                                        $result_products = $conn->query($sql_products);

                                        // Query to retrieve size and price from sizes_menu table
                                        $sql_sizes = "SELECT size, price FROM sizes_menu";
                                        $result_sizes = $conn->query($sql_sizes);

                                        if ($result_products && $result_sizes) {
                                            // Loop through the products_menu result set
                                            while ($row_products = $result_products->fetch_assoc()) {

                                                // Access the data from the products_menu table
                                                $product_name = $row_products['Product_name'];
                                                $id = $row_products['Id'];

                                                // Output the product name in a div
                                                echo "<div class='col-3 p-3 my-3 bg-secondary rounded-1 border border-black'>";
                                                echo "<h4>{$product_name}</h4>";
                                                echo "<form method='POST'>";
                                                echo "<input class='form-chech-input' type='hidden' name='product_name' value='{$product_name}'>";

                                                // Reset the internal pointer of $result_sizes after each product name
                                                $result_sizes->data_seek(0);

                                                $array = [];
                                                // Loop through the sizes_menu result set
                                                while ($row_sizes = $result_sizes->fetch_assoc()) {
                                                    // Access the data from the sizes_menu table
                                                    $size = $row_sizes['size'];
                                                    $price = $row_sizes['price'];

                                                    // Output radio buttons with values equal to price and labels equal to size
                                        
                                                    echo "<input class='form-check-input' type='radio' name='size_price' id='size{$price}' value='{$price}'>";
                                                    echo "<input class='form-chech-input' type='hidden' name='size' value='{$size}'>";
                                                    echo "<label class='form-check-label' for='size{$price}'>{$size}</label>";
                                                }
                                                // Close the div for the product
                                                echo "<input type='number' name='quantity' class='form-control' placeholder='Quantity'>";
                                                echo "<input type='submit' class='btn btn-primary' value='Submit' name='submit_items'>";
                                                echo "</form>";
                                                echo "</div>";
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Orders table -->
                        <div class="col-sm-6 d-flex">
                            <div class="col-md bg-dark p-3 rounded flex-grow-1 vh-100">
                                <h2 class="text-white">Orders</h2>
                                <div class="overflow-auto vh-70">
                                    <table class="table table-dark overflow-auto table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="cartBody">
                                            <?php
                                            include "db_config.php";
                                            $sum_total = 0;
                                            // Fetch data from the database and display in the table
                                            $sql = "SELECT * FROM `cart`";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['product_name'] . "</td>";
                                                    echo "<td>" . $row['price'] . "</td>";
                                                    echo "<td>" . $row['size'] . "</td>";
                                                    echo "<td>" . $row['quantity'] . "</td>";
                                                    echo "<td>" . $row['total'] . "</td>";
                                                    echo "</tr>";
                                                    $sum_total = $sum_total + $row['total'];
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No products found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <form method="POST">
                                    <label>Total:</label>
                                    <input type="number" class="form-control" id="inv_quantity" name="total_bill"
                                        value="<?php echo $sum_total; ?>">
                                    <input type='submit' class='btn btn-success' value='Proceed' name='place_items'>
                                </form>
                                <form method="POST"><input type='submit' class='btn btn-danger' value='Clear Carts'
                                        name='clear_items'></form>
                                <h4>Total: <?php echo $sum_total; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="script.js"></script>
</body>

</html>

<script>

</script>