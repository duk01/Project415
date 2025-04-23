<?php
session_start();
require_once 'database.php';

// Allow both members and librarians
if (!isset($_SESSION['member_id']) && !isset($_SESSION['name'])) {
    header('Location: userLogin.php');
    exit();
}

$isMember = isset($_SESSION['member_id']);
$isLibrarian = isset($_SESSION['name']);
$member_id = $isMember ? $_SESSION['member_id'] : null;
$message = "";

// Only allow members to borrow/return
if ($isMember && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['google_id'])) {
        $google_id = $_POST['google_id'];
        $title = $_POST['title'] ?? 'Untitled';
        $author = $_POST['author'] ?? 'Unknown';

        $check_stmt = $conn->prepare("SELECT book_id FROM Books WHERE google_id = ?");
        $check_stmt->bind_param("s", $google_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
            $book_id = $book['book_id'];
        } else {
            $insert_stmt = $conn->prepare("INSERT INTO Books (google_id, title, author, availability) VALUES (?, ?, ?, 'available')");
            $insert_stmt->bind_param("sss", $google_id, $title, $author);
            $insert_stmt->execute();
            $book_id = $conn->insert_id;
        }

        $_POST['book_id'] = $book_id;
        $_POST['borrow'] = true;
    }

    if (isset($_POST['borrow']) && !empty($_POST['book_id'])) {
        $book_id = $_POST['book_id'];

        $check_existing = $conn->prepare("SELECT * FROM Transactions WHERE member_id = ? AND book_id = ? AND borrowStatus = 'Issued'");
        $check_existing->bind_param("ii", $member_id, $book_id);
        $check_existing->execute();
        $existing = $check_existing->get_result();

        if ($existing->num_rows > 0) {
            $message = "⚠️ You already have this book borrowed. Please return it first.";
        } else {
            $stmt = $conn->prepare("SELECT availability FROM Books WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $book = $result->fetch_assoc();

            if ($book && $book['availability'] === 'available') {
                $borrow_stmt = $conn->prepare("INSERT INTO BorrowedBooks (member_id, book_id, borrow_date) VALUES (?, ?, NOW())");
                $borrow_stmt->bind_param("ii", $member_id, $book_id);
                $borrow_stmt->execute();

                $update_stmt = $conn->prepare("UPDATE Books SET availability = 'unavailable' WHERE book_id = ?");
                $update_stmt->bind_param("i", $book_id);
                $update_stmt->execute();

                $transaction_stmt = $conn->prepare("INSERT INTO Transactions (book_id, member_id, librarian_id, issue_date, due_date, borrowStatus) VALUES (?, ?, NULL, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'Issued')");
                $transaction_stmt->bind_param("ii", $book_id, $member_id);
                $transaction_stmt->execute();

                $message = "✅ Book borrowed successfully!";
            } else {
                $message = "⚠️ Book is not available or doesn't exist.";
            }
        }
    }

    if (isset($_POST['return']) && !empty($_POST['book_id'])) {
        $book_id = $_POST['book_id'];

        $return_stmt = $conn->prepare("DELETE FROM BorrowedBooks WHERE member_id = ? AND book_id = ?");
        $return_stmt->bind_param("ii", $member_id, $book_id);
        $return_stmt->execute();

        $update_stmt = $conn->prepare("UPDATE Books SET availability = 'available' WHERE book_id = ?");
        $update_stmt->bind_param("i", $book_id);
        $update_stmt->execute();

        $update_trans = $conn->prepare("UPDATE Transactions SET return_date = CURDATE(), borrowStatus = 'Returned' WHERE member_id = ? AND book_id = ? AND borrowStatus = 'Issued' ORDER BY issue_date DESC LIMIT 1");
        $update_trans->bind_param("ii", $member_id, $book_id);
        $update_trans->execute();

        $message = "✅ Book returned successfully!";
    }
}

// Different queries for both members and librarians
if ($isMember) {
    $borrowed_query = $conn->prepare("
        SELECT B.book_id, B.title, B.author, BB.borrow_date 
        FROM BorrowedBooks BB 
        JOIN Books B ON BB.book_id = B.book_id 
        WHERE BB.member_id = ? 
        ORDER BY BB.borrow_date DESC
    ");
    $borrowed_query->bind_param("i", $member_id);
    $borrowed_query->execute();
    $borrowed_books = $borrowed_query->get_result();
} else {
    $borrowed_books = $conn->query("
        SELECT M.name AS member_name, B.title, B.author, T.issue_date, T.due_date, T.return_date, T.borrowStatus 
        FROM Transactions T 
        JOIN Books B ON T.book_id = B.book_id 
        JOIN Members M ON T.member_id = M.member_id 
        ORDER BY T.issue_date DESC
    ");
}
?>

<!DOCTYPE html>
<html>
<head><title>Borrow/Return Books</title></head>
<body>
<?php include 'navbar.php'; ?>
<h2><?= $isMember ? "Your Borrowed Books" : "All Borrowed Transactions" ?></h2>
<?php if (!empty($message)): ?><p><strong><?= htmlspecialchars($message) ?></strong></p><?php endif; ?>

<?php if ($borrowed_books->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <?php if (!$isMember): ?><th>User</th><?php endif; ?>
        <th>Title</th><th>Author</th>
        <th><?= $isMember ? "Borrow Date" : "Issued" ?></th>
        <?php if (!$isMember): ?>
            <th>Due</th><th>Returned</th><th>Status</th>
        <?php else: ?>
            <th>Return</th>
        <?php endif; ?>
    </tr>
    <?php while ($row = $borrowed_books->fetch_assoc()): ?>
    <tr>
        <?php if (!$isMember): ?>
            <td><?= htmlspecialchars($row['member_name']) ?></td>
        <?php endif; ?>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['author']) ?></td>
        <td><?= htmlspecialchars($isMember ? $row['borrow_date'] : $row['issue_date']) ?></td>
        <?php if (!$isMember): ?>
            <td><?= htmlspecialchars($row['due_date']) ?></td>
            <td><?= $row['return_date'] ?? '—' ?></td>
            <td><?= htmlspecialchars($row['borrowStatus']) ?></td>
        <?php else: ?>
            <td>
                <form method="post">
                    <input type="hidden" name="book_id" value="<?= $row['book_id'] ?>">
                    <button type="submit" name="return">Return</button>
                </form>
            </td>
        <?php endif; ?>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p><?= $isMember ? "You haven't borrowed any books yet." : "No transactions found." ?></p>
<?php endif; ?>
</body>
</html>
