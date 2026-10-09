<?php
require_once 'db.php';
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$msg = "";
if (isset($_POST['record_sale'])) {
    $pid = $_POST['product_id'];
    $qty_sold = $_POST['quantity_sold'];
    $user_id = $_SESSION['user_id'];

    // Check Stock Availability
    $check = $conn->prepare("SELECT stock_quantity, unit_price FROM products WHERE product_id = ?");
    $check->bind_param("i", $pid);
    $check->execute();
    $prod = $check->get_result()->fetch_assoc();

    if ($prod['stock_quantity'] >= $qty_sold) {
        $total = $prod['unit_price'] * $qty_sold;

        // 1. Insert Sale Record
        $sale = $conn->prepare("INSERT INTO sales (product_id, user_id, quantity_sold, total_amount) VALUES (?, ?, ?, ?)");
        $sale->bind_param("iiid", $pid, $user_id, $qty_sold, $total);
        $sale->execute();

        // 2. Deduct Inventory Stock
        $deduct = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
        $deduct->bind_param("ii", $qty_sold, $pid);
        $deduct->execute();

        $msg = "Sale processed successfully!";
    } else {
        $msg = "Insufficient stock available for this sale!";
    }
}
?>

<h3>Record Point of Sale</h3>
<?php if($msg): ?> <p style="color: blue; font-weight: bold;"><?= $msg ?></p> <?php endif; ?>

<form action="sales.php" method="POST">
    <div class="form-group">
        <label>Select Product</label>
        <select name="product_id" required>
            <?php
            $prods = $conn->query("SELECT * FROM products WHERE stock_quantity > 0");
            while($p = $prods->fetch_assoc()) {
                echo "<option value='{$p['product_id']}'>{$p['product_name']} - \${$p['unit_price']} (Available: {$p['stock_quantity']})</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Quantity Sold</label>
        <input type="number" name="quantity_sold" min="1" value="1" required>
    </div>
    <button type="submit" name="record_sale">Complete Checkout</button>
</form>

</div></body></html>