<?php
include 'navbar.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Catalog</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .book-card { border: 1px solid #ccc; padding: 10px; margin: 10px 0; display: flex; align-items: flex-start; gap: 15px; }
        .book-card img { max-height: 150px; }
        .search-form { margin-bottom: 20px; }
        .book-info { flex: 1; }
        .filter-category { margin: 10px 0; display: block; }
        .borrow-form { margin-top: 10px; text-align: center; }
    </style>
    <script>
        function filterByCategory() {
            const selected = document.getElementById('category-filter').value.toLowerCase();
            const books = document.querySelectorAll('.book-card');
            books.forEach(book => {
                const category = book.getAttribute('data-category')?.toLowerCase() || '';
                book.style.display = (selected === '' || category.includes(selected)) ? 'flex' : 'none';
            });
        }
    </script>
</head>
<body>
    <h1>Library Book Catalog</h1>

    <form method="GET" class="search-form">
        <input type="text" name="title" placeholder="Title" value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>">
        <input type="text" name="author" placeholder="Author" value="<?php echo isset($_GET['author']) ? htmlspecialchars($_GET['author']) : ''; ?>">
        <input type="text" name="subject" placeholder="Subject" value="<?php echo isset($_GET['subject']) ? htmlspecialchars($_GET['subject']) : ''; ?>">
        <input type="text" name="isbn" placeholder="ISBN" value="<?php echo isset($_GET['isbn']) ? htmlspecialchars($_GET['isbn']) : ''; ?>">
        <button type="submit">Search</button>
    </form>

    <?php
    function buildQuery() {
        $parts = [];
        if (!empty($_GET['title'])) $parts[] = 'intitle:' . urlencode($_GET['title']);
        if (!empty($_GET['author'])) $parts[] = 'inauthor:' . urlencode($_GET['author']);
        if (!empty($_GET['subject'])) $parts[] = 'subject:' . urlencode($_GET['subject']);
        if (!empty($_GET['isbn'])) $parts[] = 'isbn:' . urlencode($_GET['isbn']);
        return implode('+', $parts);
    }

    if (!empty($_GET)) {
        $query = buildQuery();
        if (!empty($query)) {
            $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=$query&maxResults=20";
            $response = file_get_contents($apiUrl);
            if ($response !== false) {
                $data = json_decode($response, true);
                $categoriesSet = [];

                if (!empty($data['items']) && isset($data['totalItems']) && $data['totalItems'] > 0) {
                    $validCount = 0;
                    foreach ($data['items'] as $item) {
                        $volumeInfo = $item['volumeInfo'];
                        if (empty($volumeInfo['title']) || empty($volumeInfo['authors']) || strlen(trim($volumeInfo['title'])) <= 3) {
                            continue; // Skip invalid or random results like 'sdfsdf'
                        }

                        $validCount++;
                        $volumeId = $item['id'];
                        $title = $volumeInfo['title'];
                        $authors = implode(', ', $volumeInfo['authors']);
                        $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'https://via.placeholder.com/128x193?text=No+Cover';
                        $publisher = $volumeInfo['publisher'] ?? 'Unknown Publisher';
                        $publishedDate = $volumeInfo['publishedDate'] ?? 'Unknown Date';
                        $description = $volumeInfo['description'] ?? 'No Description';
                        $pageCount = isset($volumeInfo['pageCount']) && $volumeInfo['pageCount'] > 0 ? $volumeInfo['pageCount'] : 'N/A';
                        $categories = isset($volumeInfo['categories']) ? implode(', ', $volumeInfo['categories']) : 'Uncategorized';

                        if (isset($volumeInfo['categories'])) {
                            foreach ($volumeInfo['categories'] as $cat) {
                                $categoriesSet[$cat] = true;
                            }
                        }

                        echo "<div class='book-card' data-category='" . htmlspecialchars($categories) . "'>";
                        echo "<div>";
                        echo "<img src='" . htmlspecialchars($thumbnail) . "' alt='Book cover'>";
                        echo "<form method='POST' action='borrowReturn.php' class='borrow-form'>";
                        echo "<input type='hidden' name='google_id' value='" . htmlspecialchars($volumeId) . "'>";
                        echo "<input type='hidden' name='title' value='" . htmlspecialchars($title) . "'>";
                        echo "<input type='hidden' name='author' value='" . htmlspecialchars($authors) . "'>";
                        echo "<button type='submit'>Borrow</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "<div class='book-info'>";
                        echo "<strong>$title</strong><br>by $authors<br>";
                        echo "<em>Publisher:</em> $publisher<br>";
                        echo "<em>Published:</em> $publishedDate<br>";
                        echo "<em>Pages:</em> $pageCount<br>";
                        echo "<em>Categories:</em> $categories<br>";
                        echo "<p>" . substr(strip_tags($description), 0, 300) . "...</p>";
                        echo "</div></div>";
                    }

                    if ($validCount > 0) {
                        echo '<label class="filter-category">Filter by Category: </label>';
                        echo '<select id="category-filter" onchange="filterByCategory()">';
                        echo '<option value="">All</option>';
                        foreach (array_keys($categoriesSet) as $cat) {
                            echo "<option value='" . htmlspecialchars($cat) . "'>" . htmlspecialchars($cat) . "</option>";
                        }
                        echo '</select>';
                    } else {
                        echo "<p>No books found matching your criteria.</p>";
                    }
                } else {
                    echo "<p>No books found matching your criteria.</p>";
                }
            } else {
                echo "<p>Error fetching data from Google Books API.</p>";
            }
        } else {
            echo "<p>Please enter at least one search field.</p>";
        }
    } else {
        echo "<p>Enter a title, author, subject, or ISBN to search for books.</p>";
    }
    ?>
</body>
</html>
