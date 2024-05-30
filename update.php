<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch existing review data
    $stmt = $conn->prepare("SELECT rating, review FROM review WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("si", $user_id, $book_id);
    $stmt->execute();
    $stmt->bind_result($rating, $review);
    $stmt->fetch();
    $stmt->close();
  
    // If no review found, display message to create one
    if (empty($rating) && empty($review)) {
        echo "Review not found. Please create one.";
        exit;
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Update the review in the database
    $stmt = $conn->prepare("UPDATE review SET rating = ?, review = ? WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("issi", $rating, $review, $user_id, $book_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Review</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- Form for updating a review -->
                <form id="updateReviewForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" id="updateBookId" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1-5)</label>
                        <input type="number" class="form-control" id="rating" name="rating" value="<?php echo htmlspecialchars($rating); ?>" min="1" max="5" required>
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Review</label>
                        <textarea class="form-control" id="review" name="review" rows="3" required><?php echo htmlspecialchars($review); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>