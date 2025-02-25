
<?php
session_start();
include 'connection.php';

$sql = "SELECT * FROM blog_information ORDER BY upload_date DESC limit 6";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

$conn->close();
?>

<?php
$showSuccessPopup = false;

if (isset($_SESSION['upload_success']) && $_SESSION['upload_success'] === true) {
  $showSuccessPopup = true;
  unset($_SESSION['upload_success']); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Student Community Blog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../Css/home.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

  <header>
    <nav class="navbar">
      <div class="container">
        <a href="#" class="navbar-brand">Student Blog</a>
        <div class="navbar-nav">
          <a href="#">Home</a>

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
    <div class="banner">
      <div class="container">
        <h1 class="banner-title">
          <span>Stud</span>ents <span>B</span>log
        </h1>
        <p>everything that you want to know about students</p>

        <?php
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
          echo '<button><a href="admin/users.php">AdminPanel</a></button>';
        } else if (isset($_SESSION['user_id'])) {
          echo '<button><a href="crud_operation/uploadBlog.php">Create your blog</a></button>';
        } else {
          echo '<button><a href="loginProcess/loginAndRegister.php">Become a creator</a></button>';
        }
        ?>
      </div>
    </div>
  </header>

  <section class="blog" id="blog">
    <div class="container">
      <div class="title">
        <h2>Latest Blog</h2>
        <p>Recent blogs about students</p>
      </div>
      <div id="searchResults" class="search-results"></div>
      <?php if (!empty($data)) { ?>
        <div class="blog-content">
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
                <span><i class="far fa-heart"></i></span>
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
        </div>
      <?php } else { ?>
        <div class="noBlog">
          <p>Blogs Missing!</p>
        </div>
      <?php } ?>
    </div>
  </section>

  <section class="about" id="about">
    <div class="container">
      <div class="about-content">
        <div class="about-image">
          <img src="../images/aboutBackground.jpg" alt="About Students Blogs">
        </div>
        <div class="about-text">
          <div class="title">
            <h2>Welcome to Students Blogs</h2>
            <p>Your Trusted Blog Community</p>
          </div>
          <p>
            Welcome to a thriving platform where students can share their experiences, insights, and knowledge. Here, you will find blogs on education, personal growth, and the daily life of a student.
          </p>
          <p>
            Our mission is to provide avenues of free expression for students. Share with us your tales of struggle and triumph-which is your space to connect to others who have an idea of what it means to be a student.
          </p>
        </div>
      </div>
    </div>
  </section>

  <div class="overlay" id="overlay"></div>
  <div class="success-popup" id="success-popup">
    <img src="../images/success-icon.png" alt="Success">
    <p>Blog uploaded successfully!</p>
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

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
      document.getElementById('success-popup').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';

      setTimeout(() => {
        document.getElementById('success-popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
      }, 3000);
    }

    const showSuccess = <?= json_encode($showSuccessPopup) ?>;

    if (showSuccess) {
      document.getElementById('success-popup').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';

      setTimeout(() => {
        document.getElementById('success-popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
      }, 3000);
    }
  </script>
  <script src="../js/home.js"></script>
  <script src="../js/toggleLogout.js"></script>

</body>

</html>
