
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog: Editor</title>
    <link rel="stylesheet" href="../../Css/edit.css">
    <link rel="stylesheet" href="../../Css/home.css">
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

    <div class="editContainer">
        <div class="blog-upload-container">
            <h1>Upload Your Blog</h1>
            <form id="blogForm" action="uploadProcess.php" method="POST" enctype="multipart/form-data">
                <!-- Step 1: Title -->
                <div class="form-step" id="step-1">
                    <label for="title">Blog Title:</label>
                    <input type="text" id="title" name="title" placeholder="Enter blog title..." required>
                    <button type="button" class="next-btn" data-next-step="2">Next</button>
                </div>
    
                <!-- Step 2: Content -->
                <div class="form-step" id="step-2" style="display: none;">
                    <label for="content">Blog Content:</label>
                    <textarea id="content" name="content" rows="10" placeholder="Enter content here..." required></textarea>
                    <button type="button" class="prev-btn" data-prev-step="1">Previous</button>
                    <button type="button" class="next-btn" data-next-step="3">Next</button>
                </div>
    
                <!-- Step 3: Tags -->
                <div class="form-step" id="step-3" style="display: none;">
                    <label for="tags">Tags (comma-separated):</label>
                    <input type="text" id="tags" name="tags" placeholder="i.e. #something">
                    <button type="button" class="prev-btn" data-prev-step="2">Previous</button>
                    <button type="button" class="next-btn" data-next-step="4">Next</button>
                </div>
    
                <!-- Step 4: Image -->
                <div class="form-step" id="step-4" style="display: none;">
                    <label for="image">Upload Cover Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <button type="button" class="prev-btn" data-prev-step="3">Previous</button>
                    <button type="submit">Upload</button>
                </div>
            </form>
            <div id="message"></div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="social-links">
                    <a href="#" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                </div>
                <p>© 2024 Students Blog Community. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../../js/blogUploadProcess.js"></script>
</body>
</html>

