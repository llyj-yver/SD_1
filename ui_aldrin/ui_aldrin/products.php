<?php
include "db_config.php";


if (isset($_POST['submit_product_menu'])) {

    $P_name = $_POST['product_name_m'];
    $Flavored_powder = $_POST['flavored_powder_m'];

    // Prepare and bind the SQL statement
    $sql = "INSERT INTO `products_menu`(`Product_name`, `Flavored_powder`) VALUES ('$P_name','$Flavored_powder')";
    $result = $conn->query($sql);
    if ($result === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

}
if (isset($_POST['submit_size_menu'])) {
    $size = $_POST['size'];
    $pearl_content = $_POST['pearl_content'];
    $tea_content = $_POST['tea_content'];
    $flavor_content = $_POST['flavor_content'];
    $sugar_content = $_POST['sugar_content'];
    $price = $_POST['price'];

    // Prepare and bind the SQL statement
    $sql = "INSERT INTO `sizes_menu`(`size`, `pearl_content`, `tea_content`, `flavor_content`, `sugar_content`, `price`)
     VALUES ('$size','$pearl_content','$tea_content','$flavor_content','$sugar_content','$price')";
    $result = $conn->query($sql);
}
if (isset($_POST['delete_row_products'])) {
    $id = $_POST['delete_row_products'];
    // Prepare and bind the SQL statement
    $sql_delete = "DELETE FROM products_menu WHERE Id='$id'";
    // Execute the delete query
    if ($conn->query($sql_delete) === TRUE) {
        // Redirect back to the same page after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle errors if any
        echo "Error deleting record: " . $conn->error;
    }
}
if (isset($_POST['delete_row_size'])) {
    $id = $_POST['delete_row_size'];
    // Prepare and bind the SQL statement
    $sql_delete = "DELETE FROM sizes_menu WHERE Id='$id'";
    // Execute the delete query
    if ($conn->query($sql_delete) === TRUE) {
        // Redirect back to the same page after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle errors if any
        echo "Error deleting record: " . $conn->error;
    }
}
$conn->close();
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
    <div class="wrapper d-flex">
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
        <div id="products" class="article flex-grow-1">
            <h1>Products</h1>
            <div class="row g-2">
                <div class="col-sm-6 d-flex">
                    <div class="bg-dark p-3 rounded flex-grow-1">
                        <form id="productForm_m" method="POST" action="products.php">
                            <h2 class="text-white">Create Products</h2>
                            <div class="mb-3">
                                <label for="product_name_m" class="form-label text-white">Product Name</label>
                                <input type="text" class="form-control" name="product_name_m"
                                    placeholder="ex. Dark Choco">
                            </div>
                            <div class="mb-3">
                                <label for="flavored_powder_m" class="form-label text-white">Flavored Powder</label>
                                <input type="text" class="form-control" name="flavored_powder_m"
                                    placeholder="ex. Dark Choco">
                                <small id="emailHelp" class="form-text text-white">Enter the powder you will use for
                                    this product</small>
                            </div>
                            <input class="btn btn-primary" type="submit" name="submit_product_menu" value="submit">
                        </form>
                    </div>
                </div>
                <div class="col-sm-6 d-flex">
                    <div class="col bg-dark p-3 rounded flex-grow-1">
                        <form method="POST">
                            <h2 class="text-white">Add sizes</h2>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="size" class="form-label">Size</label>
                                    <input type="text" class="form-control" id="size" name="size"
                                        placeholder="ex. Dark Choco">
                                </div>
                                <div class="col mb-3">
                                    <label for="pearl_content" class="form-label">Pearl Content</label>
                                    <input type="number" class="form-control" id="pearl_content" name="pearl_content"
                                        placeholder="Enter in grams or ml">
                                </div>
                                <div class="col mb-3">
                                    <label for="tea_content" class="form-label">Tea Content</label>
                                    <input type="number" class="form-control" id="tea_content" name="tea_content"
                                        placeholder="Enter in grams or ml">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="flavor_content" class="form-label">Flavor Content</label>
                                    <input type="text" class="form-control" id="flavor_content" name="flavor_content"
                                        placeholder="Enter in grams or ml">
                                </div>
                                <div class="col mb-3">
                                    <label for="sugar_content" class="form-label">Sugar Content</label>
                                    <input type="number" class="form-control" id="sugar_content" name="sugar_content"
                                        placeholder="Enter in grams or ml">
                                </div>
                                <div class="col mb-3">
                                    <label for="quantity" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="quantity" name="price"
                                        placeholder="Enter in grams or ml">
                                </div>
                            </div>
                            <input class="btn btn-primary" type="submit" name="submit_size_menu" value="submit">
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-7 d-flex">
                    <div class="col bg-dark rounded flex-grow-1">
                        <table class="table table-dark table-hover">
                            <h4 class="m-2 text-white">Sizes</h4>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Size Name</th>
                                    <th>Pearl Content</th>
                                    <th>Tea Content</th>
                                    <th>Flavor Content</th>
                                    <th>Sugar Content</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "db_config.php";
                                // Fetch data from the database and display in the table
                                $sql = "SELECT * FROM `sizes_menu`";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['Id'] . "</td>";
                                        echo "<td>" . $row['size'] . "</td>";
                                        echo "<td>" . $row['pearl_content'] . "</td>";
                                        echo "<td>" . $row['tea_content'] . "</td>";
                                        echo "<td>" . $row['flavor_content'] . "</td>";
                                        echo "<td>" . $row['sugar_content'] . "</td>";
                                        echo "<td>" . $row['price'] . "</td>";
                                        echo "<td><form method='POST'><input type='hidden' name='delete_row_size' value='" . $row['Id'] . "'><button type='submit'>Delete</button></form></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No products found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-5 d-flex">
                    <div class="col bg-dark rounded flex-grow-1">
                        <table class="table table-dark table-hover">
                            <h4 class="m-2 text-White">Products</h4>
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Product Name</th>
                                    <th>Flavor Powder</th>
                                    <th>Availability</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "db_config.php";
                                // Check if form is submitted to delete a row
                                // Fetch data from the database and display in the table
                                $sql = "SELECT * FROM `products_menu`";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['Id'] . "</td>";
                                        echo "<td>" . $row['Product_name'] . "</td>";
                                        echo "<td>" . $row['Flavored_powder'] . "</td>";
                                        // delete button
                                        echo "<td><form method='POST'><input type='hidden' name='delete_row_products' value='" . $row['Id'] . "'><button type='submit'>Delete</button></form></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No products found</td></tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="script.js"></script>
</body>

</html>