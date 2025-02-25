<?php
// include '../connection.php';
if (isset($_SESSION['blog_id'])) {
    $blog_id = $_SESSION['blog_id'];
    $sql = "
    SELECT u.username as name, c.content as content, c.created_at as created_at 
    FROM comments c
    join users u
    on c.user_id = u.id
    join blog_information b
    on c.blog_id = b.id
    WHERE blog_id = '$blog_id' ORDER BY created_at DESC";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo "<p class='comments'><b>" . $row['name'] . ":</b> " . $row['content'] . "</p>";
    }
}
?>
