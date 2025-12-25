<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$student = null;
$result_data = null;
$total = 0;
$count = 0;

if (isset($_POST['search'])) {
    $keyword = trim($_POST['keyword']);

    // Search student by roll or email
    $stmt = $conn->prepare(
        "SELECT * FROM students 
         WHERE roll_no = ? OR email = ?"
    );
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    if ($student) {
        // Fetch result
        $stmt2 = $conn->prepare(
            "SELECT subjects.subject_name, marks.marks
             FROM marks
             JOIN subjects ON marks.subject_id = subjects.subject_id
             WHERE marks.student_id = ?"
        );
        $stmt2->bind_param("i", $student['student_id']);
        $stmt2->execute();
        $result_data = $stmt2->get_result();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Student Result</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="card" style="width:650px;">
    <h2>Search Student Result</h2>

    <form method="POST">
        <input type="text" name="keyword" placeholder="Enter Roll No or Email" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php if ($student) { ?>
        <hr>
        <h3>Student Details</h3>
        <p><b>Name:</b> <?php echo $student['name']; ?></p>
        <p><b>Roll No:</b> <?php echo $student['roll_no']; ?></p>
        <p><b>Class:</b> <?php echo $student['class']; ?></p>
        <p><b>Email:</b> <?php echo $student['email']; ?></p>

        <h3>Result</h3>
        <table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>

            <?php
            while ($row = $result_data->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['subject_name']}</td>";
                echo "<td>{$row['marks']}</td>";
                echo "</tr>";
                $total += $row['marks'];
                $count++;
            }

            $percentage = ($count > 0) ? $total / $count : 0;

            if ($percentage >= 75) $grade = "Distinction";
            elseif ($percentage >= 60) $grade = "First Class";
            elseif ($percentage >= 50) $grade = "Second Class";
            elseif ($percentage >= 35) $grade = "Pass";
            else $grade = "Fail";
            ?>

        </table>

        <p><b>Total Marks:</b> <?php echo $total; ?></p>
        <p><b>Percentage:</b> <?php echo number_format($percentage, 2); ?>%</p>
        <p><b>Grade:</b> <?php echo $grade; ?></p>

    <?php } elseif (isset($_POST['search'])) { ?>
        <p style="color:red; text-align:center;">Student not found</p>
    <?php } ?>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
