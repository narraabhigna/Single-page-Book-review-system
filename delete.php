<?php
session_start();
include 'config.php';

function handle_error($message) {
    echo json_encode(array('error' => $message));
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    handle_error("User not logged in.");
}

// Check if book_id is provided
if (isset($_GET['book_id'])) {
    $bookId = intval($_GET['book_id']); // Ensure book_id is an integer
    $userId = $_SESSION['user_id']; // Treat user_id as a string

    // Debugging statements to check the values of $bookId and $userId
    error_log("book_id: " . $bookId);
    error_log("user_id: " . $userId);

    // Prepare the SQL statement to delete the review
    $sql_delete = "DELETE FROM review WHERE book_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql_delete);

    if ($stmt === false) {
        handle_error("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("is", $bookId, $userId);

    // Execute the statement and check if the deletion was successful
    if ($stmt->execute()) {
        // Check if the review was deleted successfully
        if ($stmt->affected_rows > 0) {
            echo json_encode(array('success' => 'Review deleted successfully.'));
        } else {
            // If review doesn't exist, display an alert
            echo json_encode(array('error' => 'Review not found.'));
        }
    } else {
        handle_error("Execution failed: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();
} else {
    handle_error("Book ID not provided.");
}

// Close the database connection
$conn->close();
?>
