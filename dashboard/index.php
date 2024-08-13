<?php
require '../config.php';
$title = 'Dashboard';
require '../partials/Header.php';
require '../partials/notify.php';
?>
<div class="wrapper">
  <?php

  if (!isset($_SESSION['user_id'])) {
    header("Location: /tutionfee/auth/login.php");
    exit();
  }

  $user_id = $_SESSION['user_id'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $new_password = $_POST['new_password'];

    // Validate input (you may want to add more validation)
    if (empty($username) || empty($phone_number)) {
      $error = "All fields are required.";
    } else {
      // Hash the new password
      $ps = empty($password) ? $user['password'] : $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

      // Update user data in the database
      $update_stmt = $pdo->prepare("UPDATE users SET username = ?, phone_number = ?, password = ? WHERE id = ?");
      $result = $update_stmt->execute([$username, $phone_number, $ps, $user_id]);

      if ($result) {
        // Refresh user data after update
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $success = "Account updated successfully.";
        displayToast($success, 'success');
      } else {
        $error = "Failed to update account. Please try again.";
        displayToast($error, 'error');
      }
    }
  }




  ?>
  <div class="bg-white w-full p-20">
    <div class="profile-info mb-8 lato">
      <h2 class="text-4xl font-bold mb-4 merriweather text-gray-800"><?php echo htmlspecialchars($user['username']); ?></h2>
      <p class="text-lg text-gray-600"><span class="font-semibold">Roll Number:</span> <?php echo htmlspecialchars($user['rollNumber']); ?></p>
      <p class="text-lg text-gray-600"><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($user['email']); ?></p>
      <p class="text-lg text-gray-600"><span class="font-semibold">Phone Number:</span> <?php echo htmlspecialchars($user['phone_number']); ?></p>
      <p class="text-lg text-gray-600"><span class="font-semibold">Active Till:</span> <?php echo htmlspecialchars($user['active_till']); ?></p>
      <div class="flex flex-start items-center gap-2">
      <p class="text-lg text-gray-800"><span class="font-semibold">Status:</span> <?php echo htmlspecialchars($user['isActive']) == 0 ? 'Inactive' : 'Active'; ?></p>
      <a href="/tutionfee/auth/pay-fee.php" class="text-white bg-blue-700 rounded-md px-3 py-2 hover:bg-blue-900 transition-colors duration-200 <?php echo htmlspecialchars($user['isActive']) == 0 ? 'block' : 'hidden'; ?>">Pay Fee</a>
      </div>

    </div>
    <div class="flex flex-wrap gap-4 mb-8 raleway">
      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300" onclick="document.getElementById('updatePopup').style.display='block'">
        Update Account
      </button>
      <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300" onclick="window.location.href='/livelap/auth/delete_account.php'">
        Delete Account
      </button>
    </div>

    <div id="updatePopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Update Account</h3>
          <div class="mt-2 px-7 py-3">
            <form method="POST" action="">
              <div class="mb-4">
                <input type="text" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300">
              </div>
              <div class="mb-4">
                <input type="tel" name="phone_number" placeholder="Enter phone number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300">
              </div>
              <div class="mb-4">
                <input type="password" name="new_password" placeholder="Update Password" class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300">
              </div>
              <div class="items-center px-4 py-3">
                <button type="submit" name="update_account" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                  Update Details
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="absolute top-0 right-0 mt-4 mr-4">
          <button onclick="document.getElementById('updatePopup').style.display='none'" class="text-gray-400 hover:text-gray-600 transition duration-150">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Section For transactions -->
  <div class="p-10">
    <h2 class="text-2xl font-bold mb-4 merriweather">Transactions</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white nunito">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-2 px-4 border-b">ID</th>
            <th class="py-2 px-4 border-b">Amount</th>
            <th class="py-2 px-4 border-b">Payment Date</th>
            <th class="py-2 px-4 border-b">Subscription Type</th>
            <th class="py-2 px-4 border-b">Next Payment Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
          $stmt->execute([$user_id]);
          $transactions = $stmt->fetchAll();

          foreach ($transactions as $transaction):
          ?>
            <tr>
              <td class="py-2 text-center capitalize px-4 border-b"><?php echo htmlspecialchars($transaction['id']); ?></td>
              <td class="py-2 text-center capitalize px-4 border-b">$<?php echo htmlspecialchars($transaction['amount']); ?></td>
              <td class="py-2 text-center capitalize px-4 border-b"><?php echo htmlspecialchars($transaction['payment_date']); ?></td>
              <td class="py-2 text-center capitalize px-4 border-b"><?php echo htmlspecialchars($transaction['subscription_type']); ?></td>
              <td class="py-2 text-center capitalize px-4 border-b"><?php echo htmlspecialchars($transaction['next_payment_date']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>

</html>
</head>