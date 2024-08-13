<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title> 
    <?php
    $baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/tutionfee/';
    ?>
    <link rel="stylesheet" href="<?php echo $baseUrl . 'partials/style.css'; ?>">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js"></script>

   </head>
<body>
  <div class="wrapper">
    <h2>Login</h2>
    <?php
    require '../config.php';
    session_start();
    require '../partials/notify.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                displayToast('Login successful! Redirecting to profile...', 'success');
                echo "<script>setTimeout(function(){ window.location.href = '/tutionfee/'; }, 2000);</script>";
            } else {
                displayToast('Invalid credentials!', 'error');
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
    ?>
    <form method="POST" action="">
      <div class="input-box">
        <input type="email" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Enter password" required>
      </div>
      <div class="input-box button">
        <input type="submit" value="Login">
      </div>
      <div class="text">
        <h3>New Here? <a href="register.php">Register now</a></h3>
        <h3>Forgot Password? <a href="resetpassword.php">Reset now</a></h3>
        <h3>Go to Homepage? <a href="/tutionfee/">Go</a></h3>
      </div>
    </form>
  </div>
</body>
</html>