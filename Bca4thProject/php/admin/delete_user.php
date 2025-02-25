<?php
include '../connection.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $deleteBlogsQuery = "DELETE FROM blog_information WHERE user_id = ?";
    $stmt1 = $conn->prepare($deleteBlogsQuery);
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();

    $deleteUserQuery = "DELETE FROM users WHERE id = ?";
    $stmt2 = $conn->prepare($deleteUserQuery);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();

    if ($stmt2->affected_rows > 0) {
        echo "<script>alert('User removed successfully!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Failed to remove user.'); window.location='users.php';</script>";
    }

    $stmt1->close();
    $stmt2->close();
}

$conn->close();
?>
