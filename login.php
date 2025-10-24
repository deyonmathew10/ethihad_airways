<?php
// login.php
session_start();
require_once 'db.php';

$error = "";
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Prepared statement for safety
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? AND password=? LIMIT 1");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Ethihad Airways - Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="login-bg">
  <div class="login-container">
    <h2>✈ Ethihad Airways - Check-in</h2>
    <?php if($error): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif; ?>
    <form method="post" autocomplete="off">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" name="login">Login</button>
    </form>
    <div id="clock"></div>
  </div>

  <script>
    function updateClock(){
      document.getElementById('clock').textContent = new Date().toLocaleString();
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>
