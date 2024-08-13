<?php
session_start();
require_once 'config.php';
include './partials/Header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the latest order for the user
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user_id]);
$trdata = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trdata) {
    header('Location: index.php');
    exit();
}

?>
<div class="w-3/4 mx-auto p-5">
    <h1 class="text-3xl font-bold mb-4">Payment Confirmation</h1>
    <h2 class="text-2xl font-semibold mb-6">Thank you! Your Tution Fee has been paid!</h2>
    
    <h3 class="text-xl font-semibold mb-2">Payment Details</h3>
    <p class="mb-1">TID: <?php echo $trdata['tid']; ?></p>
    <p class="mb-1">Date: <?php echo $trdata['payment_date']; ?></p>
    <p class="mb-4">Total Amount: $<?php echo number_format($trdata['amount'], 2); ?></p>
    
    <h3 class="text-xl font-semibold mb-2">Subscription Type</h3>
    <p class="mb-4"><?php echo nl2br(htmlspecialchars($trdata['subscription_type'])); ?></p>
    
    <h3 class="text-xl font-semibold mb-2">Next Payment Date</h3>
    <p class="mb-4"><?php echo nl2br(htmlspecialchars($trdata['next_payment_date'])); ?></p>
    
    <p><a href="/tutionfee/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Move to Dashboard</a></p>
    </div>
</div>