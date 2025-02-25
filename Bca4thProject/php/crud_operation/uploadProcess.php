
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $tags = trim($_POST['tags']);
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo "User is not logged in.";
        exit;
    }

    if (empty($title) || empty($content)) {
        echo "Title and content are required.";
        exit;
    }
    if($content < 100){
        echo "Content should be more than 100 characters";
        exit;
    }

    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 10 * 1024 * 1024; // 2MB
    $banner_url = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $banner_url = $uploadDir . $fileName;

        if (!in_array($_FILES['image']['type'], $allowedFileTypes)) {
            echo "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            exit;
        }

        if ($_FILES['image']['size'] > $maxFileSize) {
            echo "File size exceeds 10MB.";
            exit;
        }

        if (!move_uploaded_file($fileTmpName, $banner_url)) {
            echo "Error uploading the image.";
            exit;
        }
    }

    $conn = mysqli_connect('localhost', 'root', '', 'blog_platform');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO blog_information (title, content, tags, upload_date, banner_url, user_id) 
            VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssi', $title, $content, $tags, $banner_url, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Blog uploaded successfully!";
            header("Location: ../Home.php");
        } else {
            echo "Error executing query: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
