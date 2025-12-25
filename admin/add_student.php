<?php
session_start();
include '../config/db.php';

/* 🔐 Admin protection */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$success = "";
$error = "";

if (isset($_POST['add'])) {
    $roll  = trim($_POST['roll']);
    $name  = trim($_POST['name']);
    $class = trim($_POST['class']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prepared statement (secure)
    $stmt = $conn->prepare(
        "INSERT INTO students (roll_no, name, class, email, password)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssss", $roll, $name, $class, $email, $password);

    if ($stmt->execute()) {
        $success = "Student added successfully";
    } else {
        $error = "Error adding student";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="card">
    <h2>Add Student</h2>

    <?php if ($success != "") { ?>
        <p style="color:green; text-align:center;"><?php echo $success; ?></p>
    <?php } ?>

    <?php if ($error != "") { ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="roll" placeholder="Roll Number" required>
        <input type="text" name="name" placeholder="Student Name" required>
        <input type="text" name="class" placeholder="Class" required>
        <input type="email" name="email" placeholder="Student Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="add">Add Student</button>
    </form>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
