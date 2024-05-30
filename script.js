$(document).ready(function () {
    // Function to open the Create Review modal and set the book ID
    window.openCreateModal = function (bookId) {
        $.ajax({
            url: "create.php", // Update with the correct PHP script handling the creation
            type: "GET",
            data: { book_id: bookId },
            success: function (response) {
                // Check if the response contains an existing review or the form for creating a new review
                if (response.hasOwnProperty('existing_review')) {
                    // Display existing review directly in the modal
                    $("#createModalBody").html(response.existing_review);
                } else {
                    // Display the form for creating a new review
                    $("#createModalBody").html(response);
                }

                $("#createModal").modal("show");

                $("#createReviewForm input:first").focus();
            },
            error: function (xhr, status, error) {
                alert("An error occurred: " + error);
            },
        });
    };

    // Delegate form submission to a static parent element
    $(document).on("submit", "#createReviewForm", function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: form.attr("method"),
            url: form.attr("action"),
            data: form.serialize(),
            success: function (response) {
                if (response.trim() === "success") {
                    alert("Review created successfully!");
                    $("#createReviewForm")[0].reset(); // Clear the form fields
                    $("#createModal").removeClass("show");
                    $("body").removeClass("modal-open");
                    $(".modal-backdrop").remove();
                } else {
                    alert("An error occurred while creating the review.");
                }
            },
            error: function (xhr, status, error) {
                alert("An error occurred: " + error);
            },
        });
    });
});


// Function to open the Read Review modal and set the book ID
function openReadModal(bookId) {
    $.ajax({
        url: 'read.php',
        type: 'GET',
        data: { book_id: bookId },
        success: function(response) {
            $('#readModal .modal-body').html(response); // Load content into modal body
            $('#readModal').modal('show'); // Show the modal
        },
        error: function(xhr, status, error) {
            alert("An error occurred: " + error);
        }
    });
}

// Function to confirm review deletion
function deleteReview(bookId) {
    if (confirm("Are you sure you want to delete this review permanently?")) {
        $.ajax({
            url: 'delete.php',
            type: 'GET',
            data: { book_id: bookId },
            success: function(response) {
                try {
                    // Parse the response as JSON
                    var jsonResponse = JSON.parse(response);
                    
                    if (jsonResponse.success) {
                        alert("Review deleted successfully!");
                        location.reload(); // Reload the page to reflect the changes
                    } else if (jsonResponse.error) {
                        alert(jsonResponse.error); // Show the error message from the server
                    } else {
                        alert("Unexpected response format.");
                    }
                } catch (e) {
                    alert("Failed to parse server response.");
                }
            },
            error: function() {
                alert("An error occurred while processing the request.");
            }
        });
    }
}



$(document).ready(function () {
    // Function to open the Update Review modal and set the book ID
    window.openUpdateModal = function (bookId) {
      $.ajax({
        url: "update.php",
        type: "GET",
        data: { book_id: bookId },
        success: function (response) {
          $("#updateModalBody").html(response);
  
$("#updateModal").modal("show");
// Set focus on the first input field of the form
$("#updateReviewForm input:first").focus();
},
error: function (xhr, status, error) {
alert("An error occurred: " + error);
},
});
};

// Delegate form submission to a static parent element
$(document).on("submit", "#updateReviewForm", function (e) {
e.preventDefault();
var form = $(this);
$.ajax({
type: form.attr("method"),
url: form.attr("action"),
data: form.serialize(),
success: function (response) {
if (response.trim() === "success") {
  alert("Review updated successfully!");
  $("#updateReviewForm")[0].reset(); // Clear the form fields
  // $("#updateModal").modal("hide");
  $("#updateModal").removeClass("show");
  $("body").removeClass("modal-open");
  $(".modal-backdrop").remove();
} else {
  alert("An error occurred while updating the review.");
}
},
error: function (xhr, status, error) {
alert("An error occurred: " + error);
},
});
});
});