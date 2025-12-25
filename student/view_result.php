<?php
session_start();
include '../config/db.php';

/* 🔐 Security check */
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['student_id'];

/* 🔍 Fetch result using JOIN */
$query = "
    SELECT subjects.subject_name, marks.marks 
    FROM marks 
    JOIN subjects ON marks.subject_id = subjects.subject_id 
    WHERE marks.student_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();


$total = 0;
$count = 0;

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Result</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="card" style="width:500px;">
    <h2>Your Result</h2>

    <table>
        <tr>
            <th>Subject</th>
            <th>Marks</th>
        </tr>

        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['subject_name'] . "</td>";
            echo "<td>" . $row['marks'] . "</td>";
            echo "</tr>";

            $total += $row['marks'];
            $count++;
        }

        if ($count > 0) {
            $percentage = $total / $count;
        } else {
            $percentage = 0;
        }

        /* 🎓 Grade calculation */
        if ($percentage >= 75) {
            $grade = "Distinction";
        } elseif ($percentage >= 60) {
            $grade = "First Class";
        } elseif ($percentage >= 50) {
            $grade = "Second Class";
        } elseif ($percentage >= 35) {
            $grade = "Pass";
        } else {
            $grade = "Fail";
        }
        ?>
    </table>
    <a href="result_pdf.php" target="_blank">
        <button>Download Result (PDF)</button>
    </a>

    <br>

    <p><b>Total Marks:</b> <?php echo $total; ?></p>
    <p><b>Percentage:</b> <?php echo number_format($percentage, 2); ?>%</p>
    <p><b>Grade:</b> <?php echo $grade; ?></p>

    <br>
    <a href="../logout.php">Logout</a>
</div>

</body>
</html>
