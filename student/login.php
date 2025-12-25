<?php
session_start();
include '../config/db.php';

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['student_id'] = $row['student_id'];
        header("Location: view_result.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="card">
    <h2>Student Login</h2>

    <?php if ($error != "") { ?>
        <p style="color:red; text-align:center; margin-bottom:10px;">
            <?php echo $error; ?>
        </p>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <a href="../index.php">Back to Admin Login</a>
</div>

</body>
</html>
