<?php
session_start();
require_once 'database.php';

// Ensure user is logged in
if (!isset($_SESSION['member_id']) && !isset($_SESSION['name'])) {
    header('Location: userLogin.php');
    exit();
}

$isMember = isset($_SESSION['member_id']);
$isLibrarian = isset($_SESSION['name']);
$member_id = $isMember ? $_SESSION['member_id'] : null;
$message = "";

// Handle fine payment
if ($isMember && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_fine'])) {
    $book_id = $_POST['book_id'];

    $fine_query = $conn->prepare("SELECT fine_id, fine_amount FROM Fines WHERE member_id = ? AND book_id = ? AND paid_status = 'unpaid' ORDER BY created_at DESC LIMIT 1");
    $fine_query->bind_param("ii", $member_id, $book_id);
    $fine_query->execute();
    $fine_result = $fine_query->get_result();

    if ($fine_result->num_rows > 0) {
        $fine = $fine_result->fetch_assoc();
        $fine_id = $fine['fine_id'];
        $fine_amount = $fine['fine_amount'];

        $balance_check = $conn->prepare("SELECT redhawk_balance FROM Members WHERE member_id = ?");
        $balance_check->bind_param("i", $member_id);
        $balance_check->execute();
        $balance_result = $balance_check->get_result();
        $balance = $balance_result->fetch_assoc()['redhawk_balance'];

        if ($balance >= $fine_amount) {
            $conn->begin_transaction();
            $update_fine = $conn->prepare("UPDATE Fines SET paid_status = 'paid' WHERE fine_id = ?");
            $update_fine->bind_param("i", $fine_id);
            $update_fine->execute();

            $update_balance = $conn->prepare("UPDATE Members SET redhawk_balance = redhawk_balance - ? WHERE member_id = ?");
            $update_balance->bind_param("di", $fine_amount, $member_id);
            $update_balance->execute();

            $conn->commit();
            $_SESSION['message'] = "✅ Fine paid successfully using Red-Hawk Dollars.";
            header("Location: borrowReturn.php");
            exit();
        } else {
            $message = "❌ Not enough Red-Hawk Dollars to pay the fine.";
        }
    }
}

// Auto update overdue transactions
$conn->query("UPDATE Transactions SET borrowStatus = 'Overdue' WHERE borrowStatus = 'Issued' AND due_date < CURDATE()");

// Insert fine for overdue if not already
$conn->query("INSERT INTO Fines (member_id, book_id, fine_amount, paid_status, created_at)
    SELECT T.member_id, T.book_id, 30, 'unpaid', NOW()
    FROM Transactions T
    WHERE T.borrowStatus = 'Overdue'
      AND NOT EXISTS (
          SELECT 1 FROM Fines F
          WHERE F.member_id = T.member_id AND F.book_id = T.book_id AND F.paid_status = 'unpaid'
      )");

// Auto-charge Red-Hawk Dollars for unpaid fines older than 7 days
$auto_charge_query = $conn->query("SELECT F.fine_id, F.member_id, F.fine_amount, F.created_at, M.redhawk_balance
    FROM Fines F
    JOIN Members M ON F.member_id = M.member_id
    WHERE F.paid_status = 'unpaid'");

while ($row = $auto_charge_query->fetch_assoc()) {
    $created_at = new DateTime($row['created_at']);
    $today = new DateTime();
    $days_diff = $today->diff($created_at)->days;

    if ($days_diff > 7) {
        $total_fine = $row['fine_amount'] + 10;
        if ($row['redhawk_balance'] >= $total_fine) {
            $update_fine = $conn->prepare("UPDATE Fines SET fine_amount = ?, paid_status = 'paid' WHERE fine_id = ?");
            $update_fine->bind_param("di", $total_fine, $row['fine_id']);
            $update_fine->execute();

            $deduct_stmt = $conn->prepare("UPDATE Members SET redhawk_balance = redhawk_balance - ? WHERE member_id = ?");
            $deduct_stmt->bind_param("di", $total_fine, $row['member_id']);
            $deduct_stmt->execute();
        }
    }
}

// Librarian sets book as returned
if ($isLibrarian && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_returned'])) {
    $trans_id = $_POST['transaction_id'] ?? null;
    if ($trans_id) {
        $stmt = $conn->prepare("UPDATE Transactions SET return_date = CURDATE(), borrowStatus = 'Returned' WHERE transaction_id = ?");
        $stmt->bind_param("i", $trans_id);
        $stmt->execute();
    }
}

// Member borrows a book
if ($isMember && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['google_id'])) {
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

    $check_existing = $conn->prepare("SELECT * FROM Transactions WHERE member_id = ? AND book_id = ? AND borrowStatus = 'Issued'");
    $check_existing->bind_param("ii", $member_id, $book_id);
    $check_existing->execute();
    $existing = $check_existing->get_result();

    if ($existing->num_rows === 0) {
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
        $message = "⚠️ You already have this book borrowed.";
    }
}

// Fetch borrowed books
if ($isMember) {
    $borrowed_query = $conn->prepare("SELECT B.book_id, B.title, B.author, BB.borrow_date, T.due_date, T.return_date, T.transaction_id
        FROM BorrowedBooks BB
        JOIN Books B ON BB.book_id = B.book_id
        JOIN Transactions T ON T.book_id = BB.book_id AND T.member_id = BB.member_id
        WHERE BB.member_id = ?
        ORDER BY BB.borrow_date DESC");
    $borrowed_query->bind_param("i", $member_id);
    $borrowed_query->execute();
    $borrowed_books = $borrowed_query->get_result();
} else {
    $borrowed_books = $conn->query("SELECT M.name AS member_name, B.title, B.author, T.issue_date, T.due_date, T.return_date, T.borrowStatus, T.transaction_id, M.member_id, B.book_id FROM Transactions T JOIN Books B ON T.book_id = B.book_id JOIN Members M ON T.member_id = M.member_id ORDER BY T.issue_date DESC");
}
?>

<!DOCTYPE html>
<html>
<head><title>Borrow/Return Books</title></head>
<body>
<?php include 'navbar.php'; ?>
<h2><?= $isMember ? "Your Borrowed Books" : "All Borrowed Transactions" ?></h2>
<?php if (isset($_SESSION['message'])): ?>
    <p><strong><?= htmlspecialchars($_SESSION['message']) ?></strong></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($borrowed_books) && $borrowed_books->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <?php if (!$isMember): ?><th>User</th><?php endif; ?>
        <th>Title</th><th>Author</th>
        <th><?= $isMember ? "Borrow Date" : "Issued" ?></th>
        <th>Due</th>
        <th><?= $isMember ? "Fine" : "Returned" ?></th>
        <?php if ($isMember): ?><th>Return Status</th><?php endif; ?>
        <?php if (!$isMember): ?><th>Status</th><th>Fine</th><th>Fine Status</th><th>Action</th><?php endif; ?>
    </tr>
    <?php while ($row = $borrowed_books->fetch_assoc()): ?>
    <tr>
        <?php if (!$isMember): ?><td><?= htmlspecialchars($row['member_name']) ?></td><?php endif; ?>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['author']) ?></td>
        <td><?= htmlspecialchars($isMember ? $row['borrow_date'] : $row['issue_date']) ?></td>
        <td><?= htmlspecialchars($row['due_date']) ?></td>
        <td>
            <?php if ($isMember): ?>
                <?php
                $fine_query = $conn->prepare("SELECT fine_id, fine_amount, paid_status FROM Fines WHERE member_id = ? AND book_id = ? ORDER BY created_at DESC LIMIT 1");
                $fine_query->bind_param("ii", $member_id, $row['book_id']);
                $fine_query->execute();
                $fine_result = $fine_query->get_result();

                if ($fine_result && $fine_result->num_rows > 0) {
                    $fine = $fine_result->fetch_assoc();
                    $fine_amount = "$" . number_format($fine['fine_amount'], 2);

                    if ($fine['paid_status'] === 'unpaid') {
                        echo "<span style='color:red;'>Fine: {$fine_amount}</span>";
                        echo "
                            <form method='post' style='display:inline; margin-left:10px;'>
                                <input type='hidden' name='book_id' value='{$row['book_id']}'>
                                <button type='submit' name='pay_fine'>Pay</button>
                            </form>
                        ";
                    } else {
                        echo "<span style='color:green;'>Fine Paid: {$fine_amount}</span>";
                    }
                } else {
                    echo "No fine";
                }
                ?>
            <?php else: ?>
                <?= $row['return_date'] ?? '—' ?>
            <?php endif; ?>
        </td>
        <?php if ($isMember): ?>
            <td>
                <?php
                $status_query = $conn->prepare("SELECT borrowStatus FROM Transactions WHERE member_id = ? AND book_id = ? ORDER BY issue_date DESC LIMIT 1");
                $status_query->bind_param("ii", $member_id, $row['book_id']);
                $status_query->execute();
                $status_result = $status_query->get_result();
                $status_row = $status_result->fetch_assoc();
                echo htmlspecialchars($status_row['borrowStatus'] ?? 'N/A');
                ?>
            </td>
        <?php endif; ?>
        <?php if (!$isMember): ?>
            <?php
            $fine_query = $conn->prepare("SELECT fine_amount, paid_status FROM Fines WHERE member_id = ? AND book_id = ? ORDER BY created_at DESC LIMIT 1");
            $fine_query->bind_param("ii", $row['member_id'], $row['book_id']);
            $fine_query->execute();
            $fine_result = $fine_query->get_result();
            if ($fine_result && $fine_result->num_rows > 0) {
                $fine = $fine_result->fetch_assoc();
                $fine_amount = "$" . number_format($fine['fine_amount'], 2);
                $fine_status = $fine['paid_status'];
            } else {
                $fine_amount = "$0.00";
                $fine_status = "none";
            }
            ?>
            <td><?= htmlspecialchars($row['borrowStatus']) ?></td>
            <td><?= $fine_amount ?></td>
            <td><?= $fine_status ?></td>
            <td>
                <?php if (is_null($row['return_date'])): ?>
                    <form method="post">
                        <input type="hidden" name="transaction_id" value="<?= $row['transaction_id'] ?>">
                        <button type="submit" name="set_returned">Mark as Returned</button>
                    </form>
                <?php else: ?>
                    —
                <?php endif; ?>
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
