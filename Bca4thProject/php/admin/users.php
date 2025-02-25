<?php
session_start();
include '../connection.php';

// Handle AJAX Search Request
if(isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    
    $query = "SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        echo "<table border='1' style='width: 80%; margin-bottom: 20px; border-collapse: collapse;'>";
        echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Action</th></tr></thead>";
        echo "<tbody>";
        
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td><a href='delete_user.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Remove</a></td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    } else {
        echo "<p>No users found</p>";
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
    <link rel="stylesheet" href="../../Css/Admin/users.css">

</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="../home.php" class="navbar-brand">Student Blog</a>
                <div class="navbar-nav">
                    <a href="../home.php">Home</a>
                    <a href="bloglist.php">Blog List</a>
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
        <h2>Users List</h2>
        <div class="tableContent">
            <?php
            // Fetch users
            $userQuery = "SELECT * FROM users where id !=1";
            $userResult = $conn->query($userQuery);

            echo "<table border='1' style='width: 80%; margin-bottom: 20px; border-collapse: collapse;'>";
            echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Action</th></tr></thead>";
            echo "<tbody>";

            if ($userResult->num_rows > 0) {
                while ($userRow = $userResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $userRow["id"] . "</td>";
                    echo "<td>" . $userRow["username"] . "</td>";
                    echo "<td>" . $userRow["email"] . "</td>";
                    echo "<td><a href='delete_user.php?id=" . $userRow["id"] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Remove</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found</td></tr>";
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

    // Add real-time search
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