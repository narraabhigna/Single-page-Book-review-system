<?php
session_start();
include 'config.php';

// Check if book_id is set
if(isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch reviews for the selected book from the database
    $stmt = $conn->prepare("SELECT User_id, Rating, Review FROM review WHERE Book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display reviews in a table
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>User_id</th><th>Rating</th><th>Review</th></tr></thead>';
        echo '<tbody>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['User_id'] . '</td>';
            echo '<td>' . $row['Rating'] . '</td>';
            echo '<td>' . $row['Review'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo 'No reviews found for this book.';
    }

    $stmt->close();
} else {
    echo 'Invalid request.';
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php if (!empty($warning_message)) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo $warning_message; ?>
                    </div>
                <?php } ?>
                <!-- Additional content can be added here -->
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
