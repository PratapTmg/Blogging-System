<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <link rel="stylesheet" href="../../Css/loginAndRegister.css">
</head>

<body>
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
    <div id="message-box" class="message success">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div id="message-box" class="message error">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <!-- Login Form -->
            <form action="process.php" method="POST" class="form" id="login-form">
                <h2 class="form-title">Login</h2>
                <label for="email" class="label-title">Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
                <label for="password" class="label-title">Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
                <button type="submit" name="login" class="btn">Login</button>
                <p class="form-footer">Don't have an account? <a href="#" id="show-register">Register</a></p>
            </form>

            <!-- Registration Form -->
            <form action="process.php" method="POST" class="form hidden" id="register-form">
                <h2 class="form-title">Registration</h2>
                <label for="name" class="label-title">Full Name</label>
                <input type="text" name="name" placeholder="Full Name" required>
                <label for="email" class="label-title">Email</label>
                <input type="email" name="email" placeholder="Email" required>
                <label for="password" class="label-title">Password</label>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register" class="btn">Register</button>
                <p class="form-footer">Already have an account? <a href="#" id="show-login">Login</a></p>
            </form>
        </div>
    </div>

    <script src="../../js/loginAndRegister.js"></script>
    <script>
        window.onload = function() {
            setTimeout(function() {
                const messageBox = document.getElementById('message-box');
                if (messageBox) {
                    messageBox.style.display = 'none';
                }
            }, 1500);
        };
    </script>
</body>

</html>