<?php
require '../config.php';
require '../sendEmail.php';
require '../partials/notify.php';
require_once '../packages/stripe-php/init.php';

\Stripe\Stripe::setApiKey('secretkey');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $userId = $_SESSION['user_id'];
    $subscriptionType = $_POST['subscription_type'];

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('SELECT active_till from users WHERE id = ?');
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Transaction Data
        $amount = 0;
        $nextPaymentDate = $result['active_till'];
        // Set amount and next payment date based on subscription type
        switch ($subscriptionType) {
            case 'monthly':
                $amount = 100;
                $nextPaymentDate = $nextPaymentDate ? date('Y-m-d H:i:s', strtotime($nextPaymentDate . ' +1 month')) : date('Y-m-d H:i:s', strtotime('+1 month'));
                break;
            case 'quarterly':
                $amount = 400;
                $nextPaymentDate = $nextPaymentDate ? date('Y-m-d H:i:s', strtotime($nextPaymentDate . ' +3 months')) : date('Y-m-d H:i:s', strtotime('+3 months'));
                break;
            case 'yearly':
                $amount = 1150;
                $nextPaymentDate = $nextPaymentDate ? date('Y-m-d H:i:s', strtotime($nextPaymentDate . ' +1 year')) : date('Y-m-d H:i:s', strtotime('+1 year'));
                break;
        }

        // Stripe Payment Intent
        $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $amount * 100, // Amount in cents
            'currency' => 'usd',
            'metadata' => ['userId' => $userId],
        ]);

        // Database Transactions
        $stmt = $pdo->prepare("INSERT INTO transactions (amount, user_id, subscription_type, next_payment_date, tid) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$amount, $userId, $subscriptionType, $nextPaymentDate, $payment_intent->id]);

        // Update user active_till
        $stmt = $pdo->prepare("UPDATE users SET isActive = ?, active_till = ? WHERE id = ?");
        $stmt->execute([TRUE, $nextPaymentDate, $userId]);

        $pdo->commit();

        // Return client secret for frontend processing
        echo json_encode(['client_secret' => $payment_intent->client_secret]);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        // Log the error for debugging
        error_log($e->getMessage());
        echo json_encode(['error' => 'Payment processing failed: ' . $e->getMessage()]);
        exit;
    }
}
