<?php
session_start();
require_once 'database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check user credentials
    $stmt = $conn->prepare("SELECT * FROM members WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password); // Use hashed passwords in real apps
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✅ Set consistent session keys
        $_SESSION['email'] = $email;
        $_SESSION['member_id'] = $row['member_id'];

        // ✅ Redirect to borrowReturn.php
        header("Location: borrowReturn.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>User Login</h2>

    <form method="post" action="">
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['member_id'])): ?>
        <p style="color: gray;">DEBUG: Logged in as Member ID <?= $_SESSION['member_id'] ?></p>
    <?php endif; ?>
</body>
</html>
