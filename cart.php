<?php
require_once 'config.php';

// Function to add a product to the cart
function addToCartHandler($user_id, $product_id, $quantity = 1) {
    global $pdo;
    
    try {
        // Check if the product is already in the cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing_item = $stmt->fetch();
        
        if ($existing_item) {
            // Update quantity if the product is already in the cart
            $new_quantity = $existing_item['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$new_quantity, $existing_item['id']]);
        } else {
            // Add new item to the cart
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        return true;
    } catch (PDOException $e) {
        error_log("Error adding to cart: " . $e->getMessage());
        return false;
    }
}

// Function to get cart items
function getCartItems($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT c.id as cart_id, c.quantity, p.* 
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching cart items: " . $e->getMessage());
        return [];
    }
}

// Function to display cart popup
function displayCartPopup($user_id) {
    $cart_items = getCartItems($user_id);
    $total = 0;
    
    echo '<h2 class="text-xl text-gray-700 merriweather py-2 text-left">Your Cart</h2>';
    
    if (empty($cart_items)) {
        echo '<p class="text-center py-10 text-gray-600 nunito">Your cart is empty.</p>';
    } else {
        echo '<ul>';
        foreach ($cart_items as $item) {
            $item_total = $item['price'] * $item['quantity'];
            $total += $item_total;
            echo "<li title='".$item['name']."'>" . substr($item['name'], 0, 20) . "... - Quantity: {$item['quantity']} - $" . number_format($item_total, 2) . "</li>";        }
        echo '</ul>';
        echo '<p class="text-left pt-5 font-semibold luto">Total: $' . number_format($total, 2) . '</p>';
    }
    echo '<div class="flex justify-center gap-2">';
    echo '<button class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded" onclick="closeCartPopup()">Close</button>';
    echo '<a href="/livelap/cart_data.php" class="mt-4 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">View Cart</a>';
}

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_to_cart':
            $product_id = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;
            
            if ($product_id) {
                $result = addToCartHandler($user_id, $product_id, $quantity);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['error' => 'Invalid product ID']);
            }
            break;
            case 'get_cart':
                displayCartPopup($user_id);
                break;
                
            default:
                echo json_encode(['error' => 'Invalid action']);
        }
        exit;
    }
    ?>
    
