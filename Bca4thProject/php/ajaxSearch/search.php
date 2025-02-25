<?php
include 'connection.php';   

if (isset($_POST["search"])) {
    $search = $conn->real_escape_string($_POST["search"]); // Prevent SQL Injection

    $sql = "SELECT title, content, tags FROM blog_information WHERE 
            title LIKE '%$search%' OR 
            content LIKE '%$search%' OR 
            tags LIKE '%$search%'";

    $result = $conn->query($sql);

    $blogs = [];
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }

    echo json_encode($blogs);
}

$conn->close();
?>
