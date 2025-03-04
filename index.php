<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        
        .frontP {
            background: url("lib.jpg") no-repeat center center/cover;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            position: relative;
        }

        .frontP::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        .frontP span {
            position: relative;
            z-index: 2;
        }

        .container {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .feature-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: scale(1.05);
        }

        .feature-icon {
            font-size: 50px;
            color: red;
        }

        .footer {
            background: gray;
            color: white;
            text-align: center;
            font-size: smaller;
            padding: 10px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .frontP {
                font-size: 1.5rem;
                height: 300px;
            }
            .feature-icon {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="frontP">
        <span>Welcome to the University Library System</span>
    </div>

    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="feature-card p-4">
                    <div class="feature-icon">📖</div>
                    <h4>Book Catalog</h4>
                    <p>Browse our collection of books available in the library.</p>
                    <a href="bookCatalog.php" class="btn btn-danger">View Books</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4">
                    <div class="feature-icon">🔄</div>
                    <h4>Borrow & Return</h4>
                    <p>Check out books or return borrowed items easily.</p>
                    <a href="borrowReturn.php" class="btn btn-danger">Borrow/Return</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4">
                    <div class="feature-icon">📜</div>
                    <h4>Placeholder</h4>
                    <p>----</p>
                    <a href="placeholder.php" class="btn btn-danger">placeholder</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
