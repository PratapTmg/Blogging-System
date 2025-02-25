
<?php
session_start(); // Start the session
include '../connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to delete your blog.";
    exit;
}

// Check if the blog ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $blog_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the blog details
    $sql = "SELECT * FROM blog_information WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $blog_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Proceed with deletion
        $delete_sql = "DELETE FROM blog_information WHERE id = ? AND user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $blog_id, $user_id);

        if ($delete_stmt->execute()) {
            $_SESSION['success'] = "Blog deleted successfully!";
            header("Location: ../myblog.php");
        } else {
            $_SESSION['error'] = "Failed to delete blog: " . mysqli_stmt_error($stmt);
            header("Location: ../myblog.php");
            exit();
        }

        $delete_stmt->close();
    } else {
        echo "Blog not found or you do not have permission to delete it.";
    }

    $stmt->close();
} else {
    echo "Invalid blog ID.";
}

$conn->close();
?>
