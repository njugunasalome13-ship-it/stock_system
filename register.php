<?php
require_once 'db.php';
require_once 'header.php';

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name)) $errors[] = "Full Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

    if (empty($errors)) {
        $hashed_pwd = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_pwd, $role);
        if ($stmt->execute()) {
            echo "<script>alert('User registered successfully!'); window.location='index.php';</script>";
        } else {
            $errors[] = "Email is already registered.";
        }
    }
}
?>

<h3>Register New Staff Member</h3>
<?php foreach($errors as $err): ?> <p class="error"><?= $err ?></p> <?php endforeach; ?>

<form id="regForm" action="register.php" method="POST" onsubmit="return validateReg()">
    <div class="form-group">
        <label>Full Name</label>
        <input type="text" id="full_name" name="full_name" required>
        <span id="nameErr" class="error"></span>
    </div>
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" id="email" name="email" required>
        <span id="emailErr" class="error"></span>
    </div>
    <div class="form-group">
        <label>Role</label>
        <select name="role">
            <option value="cashier">Cashier</option>
            <option value="admin">Administrator</option>
        </select>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" id="password" name="password" required>
        <span id="pwdErr" class="error"></span>
    </div>
    <button type="submit">Create Account</button>
</form>

<script>
function validateReg() {
    let valid = true;
    let name = document.getElementById("full_name").value.trim();
    let email = document.getElementById("email").value.trim();
    let pwd = document.getElementById("password").value;

    document.getElementById("nameErr").innerText = name ? "" : "Name is required";
    document.getElementById("emailErr").innerText = email.includes("@") ? "" : "Enter valid email";
    document.getElementById("pwdErr").innerText = pwd.length >= 6 ? "" : "Must be 6+ chars";

    if (!name || !email.includes("@") || pwd.length < 6) valid = false;
    return valid;
}
</script>
</div></body></html>