<?php
require_once 'db.php';
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<h3>Management Analytics & System Reports</h3>

<!-- REPORT 1: Low Stock Alert Report -->
<h4>Report 1: Low-Stock Inventory Alert</h4>
<table>
    <tr>
        <th>Product Name</th>
        <th>Category</th>
        <th>Current Stock</th>
        <th>Threshold</th>
    </tr>
    <?php
    $r1 = $conn->query("SELECT p.product_name, c.category_name, p.stock_quantity, p.min_threshold FROM products p JOIN categories c ON p.category_id = c.category_id WHERE p.stock_quantity <= p.min_threshold");
    while($row = $r1->fetch_assoc()):
    ?>
    <tr>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= htmlspecialchars($row['category_name']) ?></td>
        <td><b style="color:red;"><?= $row['stock_quantity'] ?></b></td>
        <td><?= $row['min_threshold'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<hr style="margin:30px 0;">

<!-- REPORT 2: Sales Summary by Product -->
<h4>Report 2: Product Performance & Revenue Summary</h4>
<table>
    <tr>
        <th>Product</th>
        <th>Total Units Sold</th>
        <th>Total Revenue Generated</th>
    </tr>
    <?php
    $r2 = $conn->query("SELECT p.product_name, SUM(s.quantity_sold) as total_units, SUM(s.total_amount) as total_rev 
                        FROM sales s JOIN products p ON s.product_id = p.product_id 
                        GROUP BY s.product_id");
    while($row = $r2->fetch_assoc()):
    ?>
    <tr>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= $row['total_units'] ?></td>
        <td>$<?= number_format($row['total_rev'], 2) ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<hr style="margin:30px 0;">

<!-- REPORT 3: Sales Log by Staff/Cashier -->
<h4>Report 3: Staff Sales Transaction Log</h4>
<table>
    <tr>
        <th>Cashier Name</th>
        <th>Product</th>
        <th>Qty Sold</th>
        <th>Amount</th>
        <th>Date/Time</th>
    </tr>
    <?php
    $r3 = $conn->query("SELECT u.full_name, p.product_name, s.quantity_sold, s.total_amount, s.sale_date 
                        FROM sales s 
                        JOIN users u ON s.user_id = u.user_id 
                        JOIN products p ON s.product_id = p.product_id 
                        ORDER BY s.sale_date DESC");
    while($row = $r3->fetch_assoc()):
    ?>
    <tr>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= $row['quantity_sold'] ?></td>
        <td>$<?= number_format($row['total_amount'], 2) ?></td>
        <td><?= $row['sale_date'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</div></body></html>