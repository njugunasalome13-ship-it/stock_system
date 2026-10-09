<?php
require_once 'db.php';
require_once 'header.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        }
    }
    $msg = "Invalid credentials provided.";
}
?>

<h3>Staff Login</h3>
<?php if($msg): ?> <p class="error"><?= $msg ?></p> <?php endif; ?>

<form action="index.php" method="POST">
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit">Login</button>
</form>
</div></body></html>