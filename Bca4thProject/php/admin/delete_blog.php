<?php
include '../connection.php';

if (isset($_GET['id'])) {
    $blog_id = intval($_GET['id']);

    $deleteQuery = "DELETE FROM blog_information WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Blog deleted successfully!'); window.location='bloglist.php';</script>";
    } else {
        echo "<script>alert('Failed to delete blog.'); window.location='bloglist.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
