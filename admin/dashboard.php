<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="card dashboard">
    <h2>Admin Dashboard</h2>

    <a href="add_student.php">➕ Add Student</a>
    <a href="add_marks.php">📝 Add Marks</a>
    <a href="search_student.php">🔍 Search Student</a>

    <a href="../logout.php" style="background:#e74c3c;">🚪 Logout</a>
</div>

</body>
</html>
