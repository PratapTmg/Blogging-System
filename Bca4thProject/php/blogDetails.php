
<?php
include('connection.php');
session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: error.php?message=Invalid Blog ID");
    exit;
}
$_SESSION['blog_id'] = $id;
// Fetch blog details
$sql = "
SELECT b.title as title, b.content as content, u.username as author, b.upload_date as created_at,b.banner_url as banner
FROM blog_information as b
left join users as u 
on b.user_id = u.id
WHERE b.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../Css/blogDetails.css">
    <link rel="stylesheet" href="../Css/blog.css">
    <link rel="stylesheet" href="../Css/home.css">
    <style>

/* Comment Section */
.comment-section {
    width: 600px;
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    /* box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1); */
}

/* Comment Form */
.comment-form {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}
p.comments{
    padding: 3px 0;
}
.comment-form textarea {
    width: 95%;
    height: 100px;
    padding: 12px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    resize: none;
    background: #f9f9f9;
}

.comment-form button {
    margin-top: 10px;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #007BFF;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.comment-form button:hover {
    background: #0056b3;
}

/* Comment List */
#comments {
    max-height: 400px;
    overflow-y: auto;
    padding: 15px;
    border-radius: 8px;
    background: #f8f9fa;
    border: 1px solid #ddd;
}

/* Individual Comment */
.comment {
    background: white;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;  /* Adds space between comments */
    box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
}


.comment .username {
    font-weight: bold;
    color: #007BFF;
    margin-bottom: 5px;
}

.comment .time {
    font-size: 12px;
    color: #777;
    margin-top: 5px;
}


    </style>
</head>
<body>
    <nav class="navbar">
      <div class="container">
        <a href="home.php" class="navbar-brand">Student Blog</a>
        <div class="navbar-nav">
          <a href="home.php">Home</a>

          <?php
          if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
            echo '<a href="admin/users.php">AdminPanel</a>';
          } else if (isset($_SESSION['user_id'])) {
            echo '<a href="myblog.php">My Blog</a>';
          } else {
            echo '<a href="#blog">Blog</a>';
          }
          ?>
          <form class="navbar-search" id="navbarSearchForm">
            <input type="text" id="searchInput" class="search-input" placeholder="Find blogs with tags" required>
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
    <div class="blog-container">
        <div class="social-icons">
        <!-- <h3>Share this blog</h3> -->
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("https://yourwebsite.com/blog.php?id=$id"); ?>" target="_blank" class="icon"><i class="fab fa-facebook"></i></a>
            <a href="#" class="icon"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode("https://yourwebsite.com/blog.php?id=$id"); ?>&title=<?php echo urlencode($row['title']); ?>" target="_blank" class="icon"><i class="fab fa-linkedin"></i></a>
        </div>

        <div class="content-container">
            <p class="category">Student Community blog</p>
            <?php
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
                echo '<p class="cta">See list of blogs? <a href="admin/bloglist.php">See blogs →</a></p>';
                      }
            else if (isset($_SESSION['user_id'])) {
            echo '<p class="cta">Create own more blogs? <a href="crud_operation/uploadBlog.php">Create blog →</a></p>';
            }else{
                echo '<p class="cta">Ready to share your ideas with the world? <a href="loginProcess/loginAndRegister.php">Start your blog →</a></p>';
            }
            ?>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='blogDetails'>";
                    echo "<h1 class='blog-title'>" . $row["title"] . "</h1>";
                    echo "<p class='author'> " . $row["author"] . " &bull; ". date("M d, Y", strtotime($row['created_at'])) ."</p>";
                    $bannerPath = 'crud_operation/' . htmlspecialchars($row['banner']);
                    if (!empty($row['banner']) && file_exists($bannerPath)) {
                        echo '<img src="' . $bannerPath . '" alt="Blog Banner" class="featured-image">';
                    } else {
                        echo '<img src="../images/background.jpg" alt="Default Banner" class="featured-image">';
                    }
                    echo "<p class='content'>" . $row["content"] . "</p>";
                    ?>
                    <div class="comment-section">
                        <h3>Leave a Comment</h3>

                        <!-- Comment Form -->
                        <form action="comments/add_comment.php" method="POST" class="comment-form">
                            <textarea name="content" placeholder="Write your comment..." required></textarea>
                            <button type="submit">Post Comment</button>
                        </form>

                        <h3>All Comments</h3>
                        <div id="comments">
                            <?php include 'comments/get_comment.php'; ?>
                        </div>
                    </div>
<?php
                    echo "</div>";
                }
            } else {
                echo "<p>No blogs found</p>";
            }
            
            ?>
        </div>
        
        <section class="blog" id="blog">
                <div class="container">
                    <div class="title">
                        <h2>Related Blog</h2>
                    </div>
                    <div class="blog-content">
                        <?php
                        // Prepare the query to fetch related blogs, excluding the current one, in random order
                        $relatedStmt = $conn->prepare("SELECT id, title, banner_url, content, upload_date FROM blog_information WHERE id != ? ORDER BY RAND() LIMIT 6");
                        $relatedStmt->bind_param("i", $id);
                        $relatedStmt->execute();
                        $relatedResult = $relatedStmt->get_result();
    
                        if ($relatedResult->num_rows > 0) {
                            while ($relatedBlog = $relatedResult->fetch_assoc()) {
                                // Dynamically assign blog values
                                $blogId = $relatedBlog['id'];
                                $blogTitle = htmlspecialchars($relatedBlog['title']);
                                $bannerPath = !empty($relatedBlog['banner_url']) ? 'crud_operation/' . htmlspecialchars($relatedBlog['banner_url']) : "../images/background.jpg";
                                $blogContent = htmlspecialchars(substr($relatedBlog['content'], 0, 80)) . '...';
                                $blogDate = date("M d, Y", strtotime($row['created_at']));
                        ?>
                                <!-- Single Blog Card -->
                                <div class="blog-item" style="width: 100%;">
                                    <div class="blog-img">
                                        <img src="<?php echo $bannerPath; ?>" alt="Blog Image">
                                    </div>
                                    <div class="blog-text">
                                        <span class="blog-date"><?php echo $blogDate; ?></span>
                                        <h2 class="blog-title"><?php echo $blogTitle; ?></h2>
                                        <p class="blog-description"><?php echo $blogContent; ?></p>
                                        <a href="blogDetails.php?id=<?php echo $blogId; ?>" class="read-more-btn">Read More</a>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p class='no-blogs'>No related blogs found.</p>";
                        }
    
                        $relatedStmt->close();
                        $conn->close();
                        ?>
                    </div>
                </div>
            </section>
    </div>
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
    <script src="../js/home.js"></script>
    <script src="../js/toggleLogout.js"></script>
</body>
</html>

