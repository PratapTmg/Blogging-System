<?php
session_start();
include '../connection.php';
var_dump($_SESSION['blog_id']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['blog_id']) && isset($_SESSION['user_id'])) {
        $blog_id = $_SESSION['blog_id'];
        $user_id = $_SESSION['user_id'];
        $content = $_POST['content'];

        if (!empty($content)) {
            $sql = "INSERT INTO comments (blog_id, user_id, content) VALUES ('$blog_id', '$user_id', '$content')";
            if ($conn->query($sql)) {
                header("Location: ../blogDetails.php?id=$blog_id");
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Comment cannot be empty!";
        }
    } else {
        header("Location: ../loginProcess/loginAndRegister.php");
    }
}
?>
