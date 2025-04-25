<?php
session_start();
require_once 'database.php';

// librarians can edit
if (isset($_SESSION['name']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_answer']) && !empty($_POST['question_id']) && !empty($_POST['answer'])) {
        $question_id = $_POST['question_id'];
        $answer = $_POST['answer'];

        $stmt = $conn->prepare("UPDATE Questions SET answer = ? WHERE id = ?");
        $stmt->bind_param("si", $answer, $question_id);
        $stmt->execute();
    }

    if (isset($_POST['delete_question']) && !empty($_POST['question_id'])) {
        $question_id = $_POST['question_id'];

        $stmt = $conn->prepare("DELETE FROM Questions WHERE id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
    }
}

// User Questions
if (isset($_SESSION['member_id']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_question'])) {
    $question = trim($_POST['user_question']);
    if (!empty($question)) {
        $stmt = $conn->prepare("INSERT INTO Questions (member_id, question) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION['member_id'], $question);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .faq-box {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .container {
            padding: 40px 20px;
        }

        @media (max-width: 768px) {
            .frontP {
                font-size: 1.5rem;
                height: 300px;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="frontP">
    <span>Ask Us Questions!</span>
</div>

<div class="container">
    <!-- Make Question public -->
    <?php
    $result = $conn->query("SELECT question, answer FROM Questions WHERE answer IS NOT NULL ORDER BY submitted_at DESC");
    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="faq-box">
            <h4><?= htmlspecialchars($row['question']) ?></h4>
            <p><?= nl2br(htmlspecialchars($row['answer'])) ?></p>
        </div>
    <?php endwhile;
    else: ?>
        <p>No answered questions yet. Check back soon!</p>
    <?php endif; ?>

    <!-- Submiting the Question -->
    <?php if (isset($_SESSION['member_id'])): ?>
        <div class="faq-box">
            <h5>Questions?:</h5>
            <form method="POST">
                <textarea name="user_question" class="form-control mb-2" rows="3" placeholder="Type your question..." required></textarea>
                <button type="submit" class="btn btn-danger">Submit Question</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- librarian Answer/Delete Questions -->
    <?php if (!empty($_SESSION['is_librarian'])): ?>
        <h3>Unanswered Questions:</h3>
        <?php
        $unanswered = $conn->query("SELECT id, question FROM Questions WHERE answer IS NULL ORDER BY submitted_at DESC");
        while ($row = $unanswered->fetch_assoc()):
        ?>
            <div class="faq-box">
                <h4><?= htmlspecialchars($row['question']) ?></h4>
                <form method="POST">
                    <input type="hidden" name="question_id" value="<?= $row['id'] ?>">
                    <textarea name="answer" rows="2" class="form-control mb-2" placeholder="Enter your answer..." required></textarea>
                    <button type="submit" name="submit_answer" class="btn btn-success btn-sm">Submit Answer</button>
                    <button type="submit" name="delete_question" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
