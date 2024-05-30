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
  
    // If review found, display message to update it
    if (!empty($rating) && !empty($review)) {
        echo "Review already exists. Please update it.";
        exit;
    }
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

        // Insert review if user exists and rating is valid
        $stmt = $conn->prepare("INSERT INTO review (User_id, Book_id, Rating, Review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $user_id, $book_id, $rating, $review);

        if ($stmt->execute()) {
            // $success_message = "Review created successfully!";
            echo "success";
        } else {
            // $warning_message = "Error: " . $stmt->error;
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
    <title>Write a Review</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- Form for creating a review -->
                <form id="createReviewForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" id="createBookId" name="book_id" value="<?php echo $_GET['book_id']; ?>">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1-5)</label>
                        <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Review</label>
                        <textarea class="form-control" id="review" name="review" rows="3" required></textarea>
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