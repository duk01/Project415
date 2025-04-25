<?php
session_start();
require_once 'database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email || empty($password)) {
        header("Location: userLogin.php?error=Invalid login credentials");
        exit;
    }

    // Hash input password to match SHA2 hashes in the DB
    $password = hash('sha256', $password);

    $stmt = $conn->prepare("SELECT name, password, member_id FROM Members WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $user['name'] ?? '';
        $_SESSION['member_id'] = $user['member_id'];
        unset($_SESSION['is_librarian']);

        // ✅ Add fine warning check
        $fine_stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM Fines WHERE member_id = ? AND paid_status = 'unpaid'");
        $fine_stmt->bind_param("i", $user['member_id']);
        $fine_stmt->execute();
        $fine_result = $fine_stmt->get_result();
        $fine_row = $fine_result->fetch_assoc();

        if ($fine_row['cnt'] > 0) {
            $_SESSION['fine_warning'] = "⚠️ You have unpaid fines. Please pay them within 7 days to avoid a \$10 late fee.";
        }

        header("Location: index.php");
        exit;
    } else {
        header("Location: userLogin.php?error=Invalid email or password");
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('Screenshot 2025-04-25 173453.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            width: 300px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <?php if (!empty($_GET['error'])) echo "<p class='error'>{$_GET['error']}</p>"; ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="empLogin.php">Staff Login</a>
        <p><a href="signup.php">Sign up here</a></p>
    </div>
</body>
</html>
