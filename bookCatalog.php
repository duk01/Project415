<?php
session_start();

$servername = "localhost";
$username = "";
$password = "";
$dbname = "libraryDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "SELECT title, author, publisher, published_year, available_copies FROM Books";
if ($search) {
    $query .= " WHERE title LIKE '%" . $conn->real_escape_string($search) . "%' 
                OR author LIKE '%" . $conn->real_escape_string($search) . "%' 
                OR publisher LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .search-input {
            padding: 10px;
            width: 80%;
        }
        .search-button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        .book-list {
            list-style-type: none;
            padding: 0;
        }
        .book-item {
            background: white;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .book-title {
            font-weight: bold;
            color: #333;
        }
        .book-author {
            font-style: italic;
            color: #777;
        }
        .available {
            color: green;
            font-weight: bold;
        }
        .borrow-button, .return-button {
            padding: 5px 10px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .borrow-button {
            background-color: #28a745;
            color: white;
        }
        .borrow-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .return-button {
            background-color: #dc3545;
            color: white;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php';?>
<!-- Display success/error messages -->
<?php if (isset($_GET['message'])): ?>
    <div class="message success"><?php echo htmlspecialchars($_GET['message']); ?></div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

    <div class="search-form">
        <form method="POST">
            <input type="text" name="search" class="search-input" placeholder="Search by title, author, or publisher" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <ul class="book-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($book = $result->fetch_assoc()): ?>
                <li class="book-item">
                    <span class="book-title"><?php echo htmlspecialchars($book['title']); ?></span> by 
                    <span class="book-author"><?php echo htmlspecialchars($book['author']); ?></span>
                    <br>Published by: <?php echo htmlspecialchars($book['publisher']); ?> (<?php echo $book['published_year']; ?>)
                    <br>Available Copies: <span class="available"><?php echo $book['available_copies']; ?></span>
                </li>
            <?php endwhile; ?>
    <br>
        <!-- Borrow Button -->
<form action="borrowBook.php" method="POST" style="display:inline;">
    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
    <button type="submit" class="borrow-button" <?php echo ($book['available_copies'] <= 0) ? 'disabled' : ''; ?>>Borrow</button>
</form>
<!-- Return Button -->
<form action="returnBook.php" method="POST" style="display:inline;">
    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
    <button type="submit" class="return-button">Return</button>
</form>
          <?php endwhile; ?>
        <?php else: ?>
            <li>No books available matching your search.</li>
        <?php endif; ?>
    </ul>
</body>
</html>
