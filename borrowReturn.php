<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "csit355pass";
$dbname = "libraryDB";

if (!isset($_SESSION['email'])) {
    header('Location: userLogin.php?error=Please login first');
    exit();
}

$member_email = $_SESSION['email'];

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query = "SELECT member_id FROM Members WHERE email = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $member_email);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

if (!$member) {
    die("Member not found.");
}

$member_id = $member['member_id'];

$query = "SELECT bb.borrowed_id, b.title AS book_name, bb.borrow_date, bb.return_date, bb.status 
          FROM BorrowedBooks bb
          JOIN Books b ON bb.book_id = b.book_id
          WHERE bb.member_id = ? AND bb.status = 'Borrowed'";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Borrowed ID: " . $row['borrowed_id'] . "<br>";
        echo "Book Name: " . $row['book_name'] . "<br>";
        echo "Borrow Date: " . $row['borrow_date'] . "<br>";
        echo "Return Date: " . $row['return_date'] . "<br>";
        echo "Status: " . $row['status'] . "<br>";
        echo "<form method='post' action=''>
                <input type='hidden' name='borrowed_id' value='" . $row['borrowed_id'] . "'>
                <button type='submit' name='return_book'>Return Book</button>
              </form>";
        echo "<hr>";
    }
} else {
    echo "No books currently borrowed.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_name'])) {
    $book_name = $_POST['book_name'];

    $query = "SELECT book_id, title, author, available_copies FROM Books WHERE title LIKE ?";
    $stmt = $mysqli->prepare($query);
    $book_name_like = '%' . $book_name . '%';
    $stmt->bind_param('s', $book_name_like);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        if ($book['available_copies'] > 0) {
            $book_id = $book['book_id'];
            $borrow_date = date('Y-m-d');
            $return_date = date('Y-m-d', strtotime('+14 days'));

            $query = "INSERT INTO BorrowedBooks (member_id, book_id, borrow_date, return_date, status) 
                      VALUES (?, ?, ?, ?, 'Borrowed')";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('iiss', $member_id, $book_id, $borrow_date, $return_date);
            if ($stmt->execute()) {
                $query = "UPDATE Books SET available_copies = available_copies - 1 WHERE book_id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('i', $book_id);
                $stmt->execute();

                echo "Successfully borrowed the book: " . $book['title'];
            } else {
                echo "Error borrowing the book.";
            }
        } else {
            echo "Sorry, no copies available for this book.";
        }
    } else {
        echo "Book not found!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_book'])) {
    $borrowed_id = $_POST['borrowed_id'];

    $query = "UPDATE BorrowedBooks SET status = 'Returned' WHERE borrowed_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $borrowed_id);
    if ($stmt->execute()) {
        $query = "SELECT book_id FROM BorrowedBooks WHERE borrowed_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $borrowed_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        
        if ($book) {
            $query = "UPDATE Books SET available_copies = available_copies + 1 WHERE book_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $book['book_id']);
            $stmt->execute();
            
            echo "Successfully returned the book.";
        }
    } else {
        echo "Error returning the book.";
    }
}
?>

<form method="post" action="">
    <label for="book_name">Enter Book Name:</label>
    <input type="text" name="book_name" required>
    <button type="submit">Borrow Book</button>
</form>
