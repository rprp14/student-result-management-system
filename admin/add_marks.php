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

/* 📥 Fetch students for dropdown */
$students = $conn->query("SELECT student_id, name, roll_no FROM students");

/* 📥 Fetch subjects for dropdown */
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects");

if (isset($_POST['submit'])) {
    $student_id = $_POST['student'];
    $subject_id = $_POST['subject'];
    $marks      = $_POST['marks'];

    /* ✅ Validation */
    if ($marks < 0 || $marks > 100) {
        $error = "Marks must be between 0 and 100";
    } else {
        /* 🔐 Secure insert */
        $stmt = $conn->prepare(
            "INSERT INTO marks (student_id, subject_id, marks)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iii", $student_id, $subject_id, $marks);

        if ($stmt->execute()) {
            $success = "Marks added successfully";
        } else {
            $error = "Error adding marks";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Marks</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="card">
    <h2>Add Marks</h2>

    <?php if ($success != "") { ?>
        <p style="color:green; text-align:center;"><?php echo $success; ?></p>
    <?php } ?>

    <?php if ($error != "") { ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <!-- 🎓 Student Dropdown -->
        <select name="student" required>
            <option value="">Select Student</option>
            <?php while ($row = $students->fetch_assoc()) { ?>
                <option value="<?php echo $row['student_id']; ?>">
                    <?php echo $row['name']; ?> (Roll: <?php echo $row['roll_no']; ?>)
                </option>
            <?php } ?>
        </select>

        <!-- 📚 Subject Dropdown -->
        <select name="subject" required>
            <option value="">Select Subject</option>
            <?php while ($row = $subjects->fetch_assoc()) { ?>
                <option value="<?php echo $row['subject_id']; ?>">
                    <?php echo $row['subject_name']; ?>
                </option>
            <?php } ?>
        </select>

        <!-- 📝 Marks -->
        <input type="number" name="marks" placeholder="Enter Marks (0–100)" required>

        <button type="submit" name="submit">Add Marks</button>
    </form>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
