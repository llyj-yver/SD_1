<?php
include "db_config.php";

// Handle form submission to add inventory
if (isset($_POST['submit_inventory'])) {
    $P_name = $_POST['inv_product_name'];
    $quantity = $_POST['inv_quantity'];

    // Prepare and execute the SQL statement to insert new inventory
    $sql = "INSERT INTO `inventory`(`product_name`, `quantity`) VALUES ('$P_name','$quantity')";
    $result = $conn->query($sql);
    if ($result === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle form submission to delete inventory
if (isset($_POST['delete_row_stock'])) {
    $id = $_POST['delete_row_stock'];
    // Prepare and execute the SQL statement to delete inventory
    $sql_delete = "DELETE FROM inventory WHERE Id='$id'";
    if ($conn->query($sql_delete) === TRUE) {
        // Redirect back to the same page after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle errors if any
        echo "Error deleting record: " . $conn->error;
    }
}

// Handle form submission to update inventory
if (isset($_POST['submit_updated_inventory'])) {


    $id = $_POST['Id'];
    $P_name = $_POST['inv_product_name'];
    $quantity1 = $_POST['inv_quantity'];

    $sql1 = "SELECT * FROM `inventory` WHERE `Id`='$id'";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $quantity2 = $row['quantity'];
        }
    }
    //adding a quantity to the existing  products
    $new_qty = $quantity1 + $quantity2;


    // Prepare and execute the SQL statement to update inventory
    $sql = "UPDATE `inventory` SET `product_name`='$P_name',`quantity`='$new_qty' WHERE `Id`='$id'";
    $result = $conn->query($sql);
    if ($result === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
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
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="pos.php" class="sidebar-link">
                        <i class="lni lni-agenda"></i>
                        <span>Point Of Sale</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="inventory.php" class="sidebar-link">
                        <i class="lni lni-agenda"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="analytics.php" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="products.php" class="sidebar-link">
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
        <div id="inventory" class="article flex-grow-1">
            <!-- Form to update item -->
            <div class="row mx-3 g-2">
                <div class="col-sm-6">
                    <div class="bg-dark p-3 rounded">
                        <?php
                        if (isset($_POST['update_row_stock'])) {
                            $id = $_POST['update_row_stock'];
                            // Prepare and execute the SQL statement to fetch item details
                            $sql_update = "SELECT * FROM inventory WHERE Id='$id'";
                            $result = $conn->query($sql_update);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['Id'];
                                    $product_name = $row['product_name'];
                                    $quantity = $row['quantity'];
                                }
                                ?>
                                <form method="POST">
                                    <h2 class="text-white">Update Item</h2>
                                    <div class="mb-3">
                                        <input type="hidden" name="Id" value="<?php echo $id; ?>">
                                        <label for="inv_product_name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="inv_product_name" name="inv_product_name"
                                            aria-describedby="emailHelp" placeholder="ex. Dark Choco"
                                            value="<?php echo $product_name; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inv_quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="inv_quantity" name="inv_quantity"
                                            placeholder="ex. 200" value="<?php echo $quantity; ?>">
                                        <small id="emailHelp" class="form-text text-white">Enter the quantity of the product (in
                                            ml or grams)</small>
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Submit" name="submit_updated_inventory">
                                </form>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <!-- Form to add new item -->
                <div class="col-sm-6">
                    <div class="col bg-dark p-3 rounded">
                        <form method="POST">
                            <h2 class="text-white">Add New Item</h2>
                            <div class="mb-3">
                                <label for="inv_product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="inv_product_name"
                                    aria-describedby="emailHelp" placeholder="ex. Dark Choco">
                            </div>
                            <div class="mb-3">
                                <label for="inv_quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="inv_quantity"
                                    placeholder="ex. 200">
                                <small id="emailHelp" class="form-text text-white">Enter the quantity of the
                                    product (in ml or grams)</small>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Submit" name="submit_inventory">
                        </form>
                    </div>
                </div>
            </div>
            <!-- Table to display inventory items -->
            <div class="row mt-4">
                <div class="col bg-dark m-3 p-3 rounded">
                    <h2 class="text-white">Inventory Items</h2>
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch data from the database and display in the table
                            $sql = "SELECT * FROM `inventory`";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['Id'] . "</td>";
                                    echo "<td>" . $row['product_name'] . "</td>";
                                    echo "<td>" . $row['quantity'] . " g</td>";
                                    echo "<td>" . $row['date'] . "</td>";
                                    // Buttons to delete and update items
                                    echo "<td>
                                            <div class='row'>
                                                <div class='col'>
                                                <form method='POST'>
                                                    <input type='hidden' name='delete_row_stock' value='" . $row['Id'] . "'>
                                                    <button type='submit' class='btn btn-danger'>Delete</button>
                                                </form>
                                                </div>
                                                <div class='col'>
                                                <form method='POST'>
                                                    <input type='hidden' name='update_row_stock' value='" . $row['Id'] . "'>
                                                    <button type='submit' class='btn btn-primary'>Update</button>
                                                </form>
                                                </div>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No products found</td></tr>";
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

<script>

</script>