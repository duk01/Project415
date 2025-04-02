<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ULMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        #header {
            text-shadow: -4px 3px 4px darkgray;
        }
        footer {
            background: gray;
            color: white;
            text-align: center;
            font-size: smaller;
            height: 40px;
            padding: 10px;
        }
        #navbarCollapse {
            text-align: center;
        }
        #loginButton {
            text-align: right;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark" style="background-color:red;">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">
            <img src="msu_logo.jpg" height="40" width="40" alt="msu logo">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="bookCatalog.php" class="nav-item nav-link">Book Catalog</a>
                <a href="borrowReturn.php" class="nav-item nav-link">Borrow/Return</a>
            </div>
        </div>

        <div class="d-flex align-items-center" id="loginButton">
            <?php if (isset($_SESSION['email'])): ?>
                <span class="text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
            <?php else: ?>
                <a href="userLogin.php" class="login-button">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<footer class="fixed-bottom">
    <p>&copy; <?php echo date("Y"); ?> University Library Management System</p>
</footer>
</body>
</html>
