<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment</title>
    <?php
    $baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/tutionfee/';
    ?>
    <link rel="stylesheet" href="<?php echo $baseUrl . 'partials/style.css'; ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <div class="wrapper">
        <h2>Add Card and Process Payment</h2>

        <form method="POST" id="payment-form" action="">
            <div id="card-element"><!--Stripe.js injects the Card Element--></div>

            <div class="input-box">
                <select name="subscription_type" required>
                    <option value="" disabled selected>Select subscription type</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="input-box button">
                <input type="submit" value="Save and Pay Now">
            </div>
            <div id="card-errors" role="alert" class="text-red-500 text-sm mb-4"></div>
        </form>
    </div>
</body>

<script>
    var stripe = Stripe('publishablekey');
    var elements = stripe.elements();
    var card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send paymentMethod.id to server
                fetch('/tutionfee/auth/process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(new FormData(form))
                })
                .then(response => response.json())
                .then(data => {
                    if (data.client_secret) {
                        return stripe.confirmCardPayment(data.client_secret, {
                            payment_method: {
                                card: card,
                            }
                        });
                    } else {
                        throw new Error(data.error || 'Unknown error');
                    }
                })
                .then(result => {
                    if (result.error) {
                        throw new Error(result.error.message);
                    }
                    // Payment successful, redirect to confirmation page
                    window.location.href = '/tutionfee/payment_confirmation.php';
                })
                .catch(error => {
                    console.error('Payment processing error:', error.message);
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                });
            }
        });
    });
</script>

</html>