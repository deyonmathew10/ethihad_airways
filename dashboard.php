<?php
// dashboard.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

// Order by class priority: First, Business, Economy. Then by checkin_time ascending.
$sql = "SELECT * FROM passengers
        ORDER BY FIELD(class, 'First', 'Business', 'Economy'), checkin_time ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Ethihad Airways - Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="dash-bg">
  <div class="nav">
    <h2>✈ Ethihad Airways - Priority Boarding</h2>
    <div class="nav-links">
      <a href="add_passenger.php">Add Passenger</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="content">
    <h3>Boarding Queue (First → Business → Economy)</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Passport</th>
          <th>Flight</th>
          <th>Seat</th>
          <th>Class</th>
          <th>Check-in Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['passenger_name']) ?></td>
              <td><?= htmlspecialchars($row['passport_no']) ?></td>
              <td><?= htmlspecialchars($row['flight_no']) ?></td>
              <td><?= htmlspecialchars($row['seat_no']) ?></td>
              <td><?= htmlspecialchars($row['class']) ?></td>
              <td><?= htmlspecialchars($row['checkin_time']) ?></td>
              <td>
                <a href="edit_passenger.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete_passenger.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete passenger?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8">No passengers yet. Add one to start.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
