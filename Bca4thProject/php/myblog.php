
<?php
session_start(); // Start the session
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to view your blog.";
    exit;
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the user's blog
$sql = "SELECT * FROM blog_information WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id); // Bind the user_id to the query
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row; // Store rows in the $data array
        }
    } else {
        $data = [];
    }

    $stmt->close();
} else {
    die("Failed to prepare statement: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../Css/blog.css">
    <link rel="stylesheet" href="../Css/home.css">
    <link rel="stylesheet" href="../Css/myblog.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="#" class="navbar-brand">Student Blog</a>
                <div class="navbar-nav">
                    <a href="home.php">Home</a>
                    <a href="#">My Blog</a>

                    <form class="navbar-search">
                        <input type="text" class="search-input" placeholder="Find blogs with tags">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '
                        <div class="loginAvatar" id="avatar" onclick="toggleDropdown()">
                            <img src="../images/avatar.jpg" alt="logo">
                            <div class="dropdown" id="dropdownMenu">
                                <button onclick="logout()">Logout</button>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </nav>
    </header>

    <section class="blog" id="blog">
        <div class="container">
            <?php
            if (isset($_SESSION['success'])) {
                echo "<div id='successMessage' class='message success'>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']); // Clear message after showing it
            }

            if (isset($_SESSION['error'])) {
                echo "<div id='errorMessage' class='message error'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']); // Clear error after showing it
            }
            ?>
            <div class="title">
                <h2>Your Blogs</h2>
            </div>
            <div class="blog-content <?php echo empty($data) ? 'no-blogs' : ''; ?>">
                <?php if (!empty($data)) { ?>
                    <?php foreach ($data as $row) { ?>
                        <div class="blog-item">
                            <div class="blog-img">
                                <?php
                                $bannerPath = 'crud_operation/' . htmlspecialchars($row['banner_url']);
                                if (!empty($row['banner_url']) && file_exists($bannerPath)) {
                                    echo '<img src="' . $bannerPath . '" alt="Blog Banner">';
                                } else {
                                    echo '<img src="../images/background.jpg" alt="Default Banner">';
                                }
                                ?>
                                <button class="edit-blog editDel" onclick="window.location.href='crud_operation/updateBlog.php?id=<?php echo $row['id']; ?>'">Edit</button>
                                <button class="delete-blog editDel" onclick="deleteBlog(<?php echo $row['id']; ?>)">Delete</button>
                            </div>
                            <div class="blog-text">
                                <span class="blog-date">
                                    <?php echo date("M d, Y", strtotime($row['upload_date'])); ?>
                                </span>
                                <h2 class="blog-title"><?php echo htmlspecialchars($row['title']); ?></h2>
                                <p class="blog-description"><?php echo htmlspecialchars($row['content']); ?></p>
                                <a href="blogDetails.php?id=<?php echo $row['id']; ?>">Read More</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No blogs found.</p><br>
                    <button><a href="/Bca4thProject/php/crud_operation/uploadBlog.php">Create your blog</a></button>
                <?php } ?>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="social-links">
                    <a href="#" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                </div>
                <p>© 2024 Students Blog Community. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        // JavaScript function for deleting blog posts with confirmation
        function deleteBlog(blogId) {
            if (confirm('Are you sure you want to delete this blog post?')) {
                window.location.href = 'crud_operation/deleteBlog.php?id=' + blogId;
            }
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Optionally add click outside to close the dropdown
        document.addEventListener('click', function(event) {
            const avatar = document.getElementById('avatar');
            const dropdown = document.getElementById('dropdownMenu');
            if (!avatar.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        function logout() {
            // Redirect to the PHP logout script
            window.location.href = 'loginProcess/logout.php';
        }

        // Hide messages after 1.5 seconds
        setTimeout(() => {
            let successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.style.display = 'none', 500); 
            }

            let errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.style.opacity = '0';
                setTimeout(() => errorMessage.style.display = 'none', 500);
            }
        }, 1500);
    </script>

    <?php $conn->close(); ?>
</body>

<script src="../js/home.js"></script>
<script src="../js/toggleLogout.js"></script>

</html>

