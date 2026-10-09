<?php
require_once 'db.php';
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// CREATE Product
if (isset($_POST['add_product'])) {
    $pname = trim($_POST['product_name']);
    $cat = $_POST['category_id'];
    $price = $_POST['unit_price'];
    $qty = $_POST['stock_quantity'];
    $min = $_POST['min_threshold'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, category_id, unit_price, stock_quantity, min_threshold) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sidii", $pname, $cat, $price, $qty, $min);
    $stmt->execute();
    header("Location: dashboard.php");
}

// UPDATE Product Stock Quantity
if (isset($_POST['update_stock'])) {
    $pid = $_POST['product_id'];
    $new_qty = $_POST['stock_quantity'];

    $stmt = $conn->prepare("UPDATE products SET stock_quantity = ? WHERE product_id = ?");
    $stmt->bind_param("ii", $new_qty, $pid);
    $stmt->execute();
    header("Location: dashboard.php");
}

// DELETE Product
if (isset($_GET['delete_id'])) {
    $pid = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>

<h3>Product & Inventory Management</h3>

<!-- CREATE PRODUCT FORM -->
<h4>Add New Product</h4>
<form action="dashboard.php" method="POST" style="margin-bottom: 30px;">
    <div style="display: flex; gap: 10px;">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <select name="category_id" required>
            <?php
            $cats = $conn->query("SELECT * FROM categories");
            while($c = $cats->fetch_assoc()) echo "<option value='{$c['category_id']}'>{$c['category_name']}</option>";
            ?>
        </select>
        <input type="number" step="0.01" name="unit_price" placeholder="Price ($)" required>
        <input type="number" name="stock_quantity" placeholder="Initial Qty" required>
        <input type="number" name="min_threshold" placeholder="Alert Qty" value="5" required>
        <button type="submit" name="add_product">Save Product</button>
    </div>
</form>

<!-- READ PRODUCTS TABLE -->
<h4>Current Inventory</h4>
<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Category</th>
        <th>Unit Price</th>
        <th>Stock Qty</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    $sql = "SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id";
    $res = $conn->query($sql);
    while($row = $res->fetch_assoc()):
        $is_low = $row['stock_quantity'] <= $row['min_threshold'];
    ?>
    <tr>
        <td><?= $row['product_id'] ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= htmlspecialchars($row['category_name']) ?></td>
        <td>$<?= number_format($row['unit_price'], 2) ?></td>
        <td><?= $row['stock_quantity'] ?></td>
        <td>
            <?php if($is_low): ?>
                <span class="badge-danger">Low Stock</span>
            <?php else: ?>
                <span class="badge-success">In Stock</span>
            <?php endif; ?>
        </td>
        <td>
            <!-- UPDATE STOCK -->
            <form action="dashboard.php" method="POST" style="display:inline-flex; gap: 5px;">
                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                <input type="number" name="stock_quantity" value="<?= $row['stock_quantity'] ?>" style="width:70px; padding:2px;">
                <button type="submit" name="update_stock" style="padding: 2px 8px;">Update</button>
            </form>
            <!-- DELETE -->
            <a href="dashboard.php?delete_id=<?= $row['product_id'] ?>" onclick="return confirm('Delete item?')" style="color:red; margin-left:8px;">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div></body></html>