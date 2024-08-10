<?php
$baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/livelap/';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $baseUrl . 'assets/logo.png'; ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $baseUrl . 'partials/fonts.css'; ?>"><?php echo "\n"; ?>
    <link rel="stylesheet" href="<?php echo $baseUrl . 'partials/bg.css'; ?>"><?php echo "\n"; ?>
    <title><?php echo $title ?? 'LiveLap - Your Own Laptop';
    session_start(); ?></title>
    <script src="https://cdn.tailwindcss.com/3.4.5"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js"></script>
</head>


<body>
    <header class="bg-white shadow-md z-10 sticky top-0">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <img src=<?php echo $baseUrl . 'assets/logo.png' ?> alt="LiveLap Icon" class="h-8 w-8 mr-2">
                <a href="/"
                    class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-blue-950 text-transparent bg-clip-text raleway">LiveLap</a>
            </div>
            <nav>
                <ul class="flex space-x-6 nunito">
                    <li><a href="/livelap/" class="text-gray-600 hover:text-gray-900">Home</a></li>
                    <li><a href="/livelap/products.php" class="text-gray-600 hover:text-gray-900">Products</a></li>
                    <li><a href="/livelap/contact.php" class="text-gray-600 hover:text-gray-900">Contact</a></li>
                    <li><a href="/livelap/about.php" class="text-gray-600 hover:text-gray-900">About</a></li>
                </ul>
            </nav>
            <div class="flex space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/livelap/dashboard"
                        class="bg-blue-500 hover:bg-blue-600 raleway text-white font-semibold py-2 px-4 rounded">Dashboard</a>
                    <a href="/livelap/auth/logout.php"
                        class="raleway text-red-500 font-semibold py-2 px-4 rounded">Logout</a>
                    <button onclick="displayCartPopup()"
                        class="bg-green-500 hover:bg-green-600 text-white raleway font-semibold py-2 px-4 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        Cart
                    </button>
                    <div id="cart-popup" class="hidden fixed top-16 p-5 rounded-md right-4 w-64 bg-white shadow-lg z-50">
                       
                    </div>


                <?php else: ?>
                    <a href="/livelap/auth/login.php"
                        class="bg-purple-500 hover:bg-purple-600 text-white raleway font-semibold py-2 px-4 rounded">Login</a>
                    <a href="/livelap/auth/register.php"
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