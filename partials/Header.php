<?php
$baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/tutionfee/';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $baseUrl . 'assets/logo.png'; ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $baseUrl . 'partials/fonts.css'; ?>"><?php echo "\n"; ?>
    <link rel="stylesheet" href="<?php echo $baseUrl . 'partials/bg.css'; ?>"><?php echo "\n"; ?>
    <title><?php echo $title ?? 'Tutionfee - Pay Your Fees';
    session_start(); ?></title>
    <script src="https://cdn.tailwindcss.com/3.4.5"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js"></script>
</head>


<body>
    <header class="bg-white shadow-md z-10 sticky top-0">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <img src=<?php echo $baseUrl . 'assets/logo.png' ?> alt="tutionfee Icon" class="h-8 w-8 mr-2">
                <a href="/"
                    class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-blue-950 text-transparent bg-clip-text raleway">tutionfee</a>
            </div>
            <div class="flex space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/tutionfee/dashboard"
                        class="bg-blue-500 hover:bg-blue-600 raleway text-white font-semibold py-2 px-4 rounded">Dashboard</a>
                    <a href="/tutionfee/auth/logout.php"
                        class="raleway text-red-500 font-semibold py-2 px-4 rounded">Logout</a>

                <?php else: ?>
                    <a href="/tutionfee/auth/login.php"
                        class="bg-purple-500 hover:bg-purple-600 text-white raleway font-semibold py-2 px-4 rounded">Login</a>
                    <a href="/tutionfee/auth/register.php"
                        class="bg-blue-500 hover:bg-blue-600 text-white raleway font-semibold py-2 px-4 rounded">Get
                        Started</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        function displayCartPopup() {
            document.getElementById('cart-popup').style.display = 'block';
            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_cart',
            })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('cart-popup').innerHTML = html;
                });
        }
        
        function closeCartPopup() {
            document.getElementById('cart-popup').style.display = 'none';
        }
    </script>