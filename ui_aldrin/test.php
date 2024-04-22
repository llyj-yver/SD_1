<?php
include "db_config.php";
$P_name = 'okinawa';
$price = 100;
$newQuantities = [
    'pearl' => 800,
    'tea' => 800,
    $P_name => 800,
    'sugar' => 800
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
        echo "Quantity of $product updated successfully.<br>";
    } else {
        echo "Error updating quantity of $product: " . $conn->error . "<br>";
    }
}

$sql_sizes1 = "SELECT * FROM `sizes_menu` WHERE `price`= '$price'";
$result90 = $conn->query($sql_sizes1);
$pearl_content_inv = 0;
$tea_content_inv = 0;
$flavor_content_inv = 0;
$sugar_content_inv = 0;

if ($result90->num_rows > 0) {
    while ($row = $result90->fetch_assoc()) {
        $pearl_content_inv = $row['pearl_content'];
        $tea_content_inv = $row['tea_content'];
        $flavor_content_inv = $row['flavor_content'];
        $sugar_content_inv = $row['sugar_content'];
    }
}
echo $pearl_content_inv;
echo $tea_content_inv;
echo $flavor_content_inv;
echo $sugar_content_inv;

$sql = "SELECT
    SUM(CASE WHEN product_name = 'pearl' THEN quantity ELSE 0 END) AS quantity_pearl,
    SUM(CASE WHEN product_name = 'tea' THEN quantity ELSE 0 END) AS quantity_tea,
    SUM(CASE WHEN product_name = '$P_name' THEN quantity ELSE 0 END) AS quantity_powder,
    SUM(CASE WHEN product_name = 'sugar' THEN quantity ELSE 0 END) AS quantity_sugar
FROM
    inventory
WHERE
    product_name IN ('pearl', 'tea', '$P_name', 'sugar');
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the first (and only) row
    $row = $result->fetch_assoc();

    $quantity_pearl = $row['quantity_pearl'];
    $quantity_tea = $row['quantity_tea'];
    $quantity_flavor = $row['quantity_powder'];
    $quantity_sugar = $row['quantity_sugar'];
}

echo $quantity_pearl;
echo $quantity_tea;
echo $quantity_flavor;
echo $quantity_sugar;

$updt_P = $quantity_pearl - $pearl_content_inv;
$updt_T = $quantity_tea - $tea_content_inv;
$updt_F = $quantity_flavor - $flavor_content_inv;
$updt_S = $quantity_sugar - $sugar_content_inv;

echo $updt_P;
echo $updt_T;
echo $updt_F;
echo $updt_S;

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
        echo "Quantity of $product updated successfully.<br>";
    } else {
        echo "Error updating quantity of $product: " . $conn->error . "<br>";
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
    product_name IN ('pearl', 'tea', '$P_name', 'sugar');
";

$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // Output data of the first (and only) row
    $row = $result->fetch_assoc();

    $quantity_pearl = $row['quantity_pearl'];
    $quantity_tea = $row['quantity_tea'];
    $quantity_flavor = $row['quantity_powder'];
    $quantity_sugar = $row['quantity_sugar'];
}

echo "new:$quantity_pearl";
echo "new:$quantity_tea";
echo "new:$quantity_flavor";
echo "new:$quantity_sugar";