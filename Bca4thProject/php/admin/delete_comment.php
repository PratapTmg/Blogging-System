<?php
include '../connection.php';

if (isset($_GET['id'])) {
    $commentId = intval($_GET['id']);

    $deleteQuery = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Comment deleted successfully!'); window.location='commentlist.php';</script>";
    } else {
        echo "<script>alert('Failed to delete comments.'); window.location='commentlist.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>