<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: userLogin.php");
    exit();
}

$member_id = $_SESSION['member_id'];

$query = $conn->prepare("
    SELECT B.title, B.author, T.issue_date, T.due_date, T.return_date, T.borrowStatus
    FROM Transactions T
    JOIN Books B ON T.book_id = B.book_id
    WHERE T.member_id = ?
    ORDER BY T.issue_date DESC
");
$query->bind_param("i", $member_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow History</title>
</head>
<body>
<?php include 'navbar.php'; ?>
<h2>Your Borrow History</h2>

<?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Issued</th>
            <th>Due</th>
            <th>Returned</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author']) ?></td>
                <td><?= htmlspecialchars($row['issue_date']) ?></td>
                <td><?= htmlspecialchars($row['due_date']) ?></td>
                <td><?= $row['return_date'] ?? '—' ?></td>
                <td><?= $row['borrowStatus'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>You haven’t borrowed any books yet.</p>
<?php endif; ?>
</body>
</html>
