<?php
// edit_passenger.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$id = (int)$_GET['id'];

// fetch existing
$stmt = $conn->prepare("SELECT * FROM passengers WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}
$pass = $res->fetch_assoc();
$stmt->close();

$error = "";
if (isset($_POST['update'])) {
    $name = trim($_POST['passenger_name'] ?? '');
    $passport = trim($_POST['passport_no'] ?? '');
    $flight = trim($_POST['flight_no'] ?? '');
    $seat = trim($_POST['seat_no'] ?? '');
    $class = $_POST['class'] ?? 'Economy';

    if ($name === '') {
        $error = "Passenger name is required.";
    } else {
        $stmt = $conn->prepare("UPDATE passengers SET passenger_name=?, passport_no=?, flight_no=?, seat_no=?, class=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $passport, $flight, $seat, $class, $id);
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
<title>Edit Passenger - Ethihad</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="dash-bg">
  <div class="form-container">
    <h2>Edit Passenger</h2>
    <?php if ($error): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif; ?>
    <form method="post">
      <input type="text" name="passenger_name" value="<?=htmlspecialchars($pass['passenger_name'])?>" required><br>
      <input type="text" name="passport_no" value="<?=htmlspecialchars($pass['passport_no'])?>"><br>
      <input type="text" name="flight_no" value="<?=htmlspecialchars($pass['flight_no'])?>" required><br>
      <input type="text" name="seat_no" value="<?=htmlspecialchars($pass['seat_no'])?>"><br>
      <label>Class</label><br>
      <select name="class" required>
        <option value="First" <?= $pass['class']=='First' ? 'selected' : '' ?>>First</option>
        <option value="Business" <?= $pass['class']=='Business' ? 'selected' : '' ?>>Business</option>
        <option value="Economy" <?= $pass['class']=='Economy' ? 'selected' : '' ?>>Economy</option>
      </select><br><br>
      <button type="submit" name="update">Update</button>
    </form>
    <p><a href="dashboard.php">← Back to Dashboard</a></p>
  </div>
</body>
</html>
