
<?php
session_start();

include '../connection.php';

// Handle API request for fetching blog data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['api'])) {
    header('Content-Type: application/json');

    $blog_id = intval($_GET['id']);

    $sql = "SELECT * FROM blog_information WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $blog_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'Blog not found.']);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Error preparing statement.']);
    }

    mysqli_close($conn);
    exit;
}

// Handle POST request for updating blog
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = intval($_POST['blog_id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $tags = trim($_POST['tags']);
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        die("User is not logged in.");
    }

    if (empty($title) || empty($content)) {
        die("Title and content are required.");
    }

    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 10 * 1024 * 1024; // 10MB
    $banner_url = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $banner_url = $uploadDir . $fileName;

        if (!in_array($_FILES['image']['type'], $allowedFileTypes)) {
            die("Invalid file type.");
        }

        if ($_FILES['image']['size'] > $maxFileSize) {
            die("File size exceeds 10MB.");
        }

        if (!move_uploaded_file($fileTmpName, $banner_url)) {
            die("Error uploading image.");
        }
    }

    $sql = "UPDATE blog_information SET title = ?, content = ?, tags = ?, banner_url = IFNULL(?, banner_url) WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssii', $title, $content, $tags, $banner_url, $blog_id, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Blog updated successfully!";
            header("Location: ../myblog.php");
            exit();
        } else {
            $_SESSION['error'] = "Error updating blog: " . mysqli_stmt_error($stmt);
            header("Location: ../myblog.php");
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
    <link rel="stylesheet" href="../../Css/edit.css">
    <link rel="stylesheet" href="../../Css/home.css">
    <link rel="stylesheet" href="../../Css/Crud_css/updateBlog.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="../Home.php" class="navbar-brand">Student Blog</a>
            <div class="navbar-nav">
                <a href="../Home.php">Home</a>
                <a href="#">Blog</a>
                <a href="#">About</a>
            </div>
        </div>
    </nav>

    <div class="blog-edit-container">
        <h1>Edit Your Blog</h1>
        <form id="editBlogForm" action="" method="POST" enctype="multipart/form-data">
            <div class="formContainer">
                <input type="hidden" id="blog_id" name="blog_id">
    
                <div class="form-group">
                    <label for="title">Blog Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
    
                <div class="form-group">
                    <label for="content">Blog Content:</label>
                    <textarea id="content" name="content" rows="10" required></textarea>
                </div>
    
                <div class="form-group">
                    <label for="tags">Tags (comma-separated):</label>
                    <input type="text" id="tags" name="tags">
                </div>
    
                <div class="form-group">
                    <label for="image">Upload Cover Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small>Leave blank if you don't want to change the image.</small>
                </div>
            </div>

            <button type="submit">Update Blog</button>
        </form>
    </div>

    <footer>
        <div class="container">
            <p>© 2024 Students Blog Community. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const blogId = urlParams.get('id');

        if (!blogId) {
            alert('No blog ID provided.');
            return;
        }

        try {
            const response = await fetch(`?id=${blogId}&api=true`);
            const data = await response.json();

            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('blog_id').value = data.id;
            document.getElementById('title').value = data.title;
            document.getElementById('content').value = data.content;
            document.getElementById('tags').value = data.tags;
        } catch (error) {
            console.error('Error fetching blog data:', error);
        }
    });
    </script>
</body>
</html>
