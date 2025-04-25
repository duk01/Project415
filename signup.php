<?php
session_start();
require_once 'database.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!$email || empty($name) || empty($password) || empty($confirm_password)) {
        $error = "Please fill out all fields correctly";
    } elseif (!str_ends_with($email, "@montclair.edu")) {
        $error = "Only Montclair email addresses are allowed";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $hashedPassword = hash("sha256", $password);

        $stmt = $conn->prepare("INSERT INTO Members (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now log in now";
        } else {
            $error = "Email already registered";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .signup-container {
            width: 350px;
            padding: 25px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn-danger {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <h2 class="text-center mb-3">Create an Account</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-2">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-2">
            <input type="email" name="email" class="form-control" placeholder="Montclair Email" required>
        </div>
        <div class="mb-2">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-danger">Sign Up</button>
        <p class="mt-3 text-center"><a href="userLogin.php">Already have an account? Log in</a></p>
    </form>
</div>

</body>
</html>
