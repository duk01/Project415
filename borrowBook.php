<?php
session_start();

// Database connection
$servername = "localhost";
$username = ""; // Fill in your username
$password = ""; // Fill in your password
$dbname = "libraryDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: userLogin.php");
    exit;
}

// Get book ID from POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Fetch member ID from session (assuming it's stored during login)
    $email = $_SESSION['email'];
    $member_query = "SELECT member_id FROM Members WHERE email = ?";
    $stmt = $conn->prepare($member_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $member_result = $stmt->get_result();

    if ($member_result->num_rows > 0) {
        $member = $member_result->fetch_assoc();
        $member_id = $member['member_id'];

        // Check if the book is available
        $book_query = "SELECT available_copies FROM Books WHERE book_id = ?";
        $stmt = $conn->prepare($book_query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $book_result = $stmt->get_result();

        if ($book_result->num_rows > 0) {
            $book = $book_result->fetch_assoc();
            $available_copies = $book['available_copies'];

            if ($available_copies > 0) {
                // Insert into Transactions table
                $transaction_query = "INSERT INTO Transactions (book_id, member_id, issue_date, due_date, status) 
                                     VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'Issued')";
                $stmt = $conn->prepare($transaction_query);
                $stmt->bind_param("ii", $book_id, $member_id);
                $stmt->execute();

                // Update available_copies in Books table
                $update_query = "UPDATE Books SET available_copies = available_copies - 1 WHERE book_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("i", $book_id);
                $stmt->execute();

                // Redirect with success message
                header("Location: bookCatalog.php?message=Book borrowed successfully");
                exit;
            } else {
                header("Location: bookCatalog.php?error=Book not available");
                exit;
            }
        } else {
            header("Location: bookCatalog.php?error=Book not found");
            exit;
        }
    } else {
        header("Location: userLogin.php?error=User not found");
        exit;
    }
} else {
    header("Location: bookCatalog.php");
    exit;
}
?>
