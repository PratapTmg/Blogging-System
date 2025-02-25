<?php
session_start();
include '../connection.php';

// Handle AJAX Search Request
if(isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    
    $query = "SELECT u.id AS user_id, u.username, u.email, b.id AS blog_id, b.title, b.content, b.upload_date as created_at 
              FROM blog_information b
              JOIN users u ON u.id = b.user_id
              WHERE b.title LIKE '%$search%' 
              OR b.content LIKE '%$search%'
              OR u.username LIKE '%$search%'
              OR u.email LIKE '%$search%'
              ORDER BY b.upload_date DESC";
              
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        echo "<table border='1' style='width: 80%; margin: 20px auto; border-collapse: collapse;'>";
        echo "<thead><tr><th>Blog ID</th><th>Title</th><th>Content</th><th>User</th><th>User Email</th><th>Created At</th><th>Action</th></tr></thead>";
        echo "<tbody>";
        
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["blog_id"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["content"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["created_at"] . "</td>";
            echo "<td><a href='delete_blog.php?id=" . $row["blog_id"] . "' onclick='return confirm(\"Are you sure you want to delete this blog?\")'>Remove</a></td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    } else {
        echo "<p>No blogs found</p>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../../Css/home.css">
    <link rel="stylesheet" href="../../Css/Admin/bloglist.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="../home.php" class="navbar-brand">Student Blog</a>
                <div class="navbar-nav">
                    <a href="../home.php">Home</a>
                    <a href="users.php">UsersList</a>
                    <a href="commentlist.php">CommentList</a>
                    <form class="navbar-search" id="navbarSearchForm">
                        <input type="text" id="searchInput" class="search-input" placeholder="Find user or blog" required>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '
                        <div class="loginAvatar" id="avatar" onclick="toggleDropdown()">
                            <img src="../../images/avatar.jpg" alt="logo">
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

    <div class="bodyContainer">
    <h2>Blogs List</h2>
        <div class="tableContent">
            <?php
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $query = "
                SELECT u.id AS user_id, u.username, u.email, b.id AS blog_id, b.title, b.content, b.upload_date as created_at
                FROM blog_information b
                JOIN users u ON u.id = b.user_id
                ORDER BY b.upload_date DESC
            ";

            $blogResult = $conn->query($query);


            echo "<table border='1' style='width: 80%; margin: 20px auto; border-collapse: collapse;'>";
            echo "<thead><tr><th>Blog ID</th><th>Title</th><th>Content</th><th>User</th><th>User Email</th><th>Created At</th><th>Action</th></tr></thead>";
            echo "<tbody>";

            if ($blogResult->num_rows > 0) {
                while ($blogRow = $blogResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $blogRow["blog_id"] . "</td>";
                    echo "<td>" . $blogRow["title"] . "</td>";
                    echo "<td>" . $blogRow["content"] . "</td>";
                    echo "<td>" . $blogRow["username"] . "</td>";
                    echo "<td>" . $blogRow["email"] . "</td>";
                    echo "<td>" . $blogRow["created_at"] . "</td>";
                    echo "<td><a href='delete_blog.php?id=" . $blogRow["blog_id"] . "' onclick='return confirm(\"Are you sure you want to delete this blog?\")'>Remove</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No blogs found</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";

            $conn->close();
            ?>
        </div>
        <div id="searchResults" class="blog-content"></div>
    </div>

    <script src="../../js/adminloginProcess.js"></script>
    <script>
    document.getElementById('navbarSearchForm').onsubmit = function(e) {
        e.preventDefault();
        
        const searchInput = document.getElementById('searchInput');
        const searchTerm = searchInput.value.trim();
        
        if(searchTerm.length > 0) {
            const formData = new FormData();
            formData.append('search', searchTerm);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('searchResults').innerHTML = data;
                document.querySelector('.tableContent').style.display = 'none';
                document.getElementById('searchResults').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('searchResults').innerHTML = '<p>Error occurred while searching</p>';
            });
        } else {
            document.getElementById('searchResults').innerHTML = '';
            document.getElementById('searchResults').style.display = 'none';
            document.querySelector('.tableContent').style.display = 'flex';
        }
    };

    // Add real-time search reset
    document.getElementById('searchInput').addEventListener('input', function() {
        if(this.value.trim() === '') {
            document.getElementById('searchResults').innerHTML = '';
            document.getElementById('searchResults').style.display = 'none';
            document.querySelector('.tableContent').style.display = 'flex';
        }
    });
    </script>
</body>
</html>