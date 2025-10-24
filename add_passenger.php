<?php
// add_passenger.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

$error = "";
if (isset($_POST['add'])) {
    $name = trim($_POST['passenger_name'] ?? '');
    $passport = trim($_POST['passport_no'] ?? '');
    $flight = trim($_POST['flight_no'] ?? '');
    $seat = trim($_POST['seat_no'] ?? '');
    $class = $_POST['class'] ?? 'Economy';

    if ($name === '') {
        $error = "Passenger name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO passengers (passenger_name, passport_no, flight_no, seat_no, class) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $passport, $flight, $seat, $class);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Add Passenger - Ethihad</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="dash-bg">
  <div class="form-container">
    <h2>Add Passenger</h2>
    <?php if ($error): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif; ?>
    <form method="post">
      <input type="text" name="passenger_name" placeholder="Passenger Name" required><br>
      <input type="text" name="passport_no" placeholder="Passport No (optional)"><br>
      <input type="text" name="flight_no" placeholder="Flight No (e.g., EY123)" required><br>
      <input type="text" name="seat_no" placeholder="Seat No (optional)"><br>
      <label>Class</label><br>
      <select name="class" required>
        <option value="First">First</option>
        <option value="Business">Business</option>
        <option value="Economy" selected>Economy</option>
      </select><br><br>
      <button type="submit" name="add">Add Passenger</button>
    </form>
    <p><a href="dashboard.php">← Back to Dashboard</a></p>
  </div>
</body>
</html>
