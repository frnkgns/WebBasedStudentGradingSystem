<?php
session_start();
include('connection_db.php'); // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: a.php"); // Redirect to login page if not logged in
    exit();
}

// Check if course_id is set
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} else {
    echo "No course selected.";
    exit();
}

// Get student ID from session
$student_id = $_SESSION['user_id'];
if (empty($student_id)) {
    echo "User ID not found in session.";
    exit();
}

// Prepare the SQL query to fetch grades
$query = "SELECT Grades.quiz_1_score, Grades.quiz_2_score, Grades.quiz_3_score, 
                 Grades.activity_1_score, Grades.activity_2_score, 
                 Grades.prelim_grade, Grades.midterm_grade, Grades.final_grade, 
                 Grades.overall_grade 
          FROM Grades 
          JOIN Enrollment ON Grades.enrollment_id = Enrollment.enrollment_id 
          WHERE Enrollment.student_id = ? AND Enrollment.course_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $student_id, $course_id);

// Execute the statement
if ($stmt->execute()) {
    $result = $stmt->get_result();

    // Check if grades were returned
    if ($result->num_rows > 0) {
        // Display grades
        while ($row = $result->fetch_assoc()) {
            // Output the grades
            echo "Quiz 1 Score: " . $row['quiz_1_score'] . "<br>";
            echo "Quiz 2 Score: " . $row['quiz_2_score'] . "<br>";
            echo "Quiz 3 Score: " . $row['quiz_3_score'] . "<br>";
            echo "Activity 1 Score: " . $row['activity_1_score'] . "<br>";
            echo "Activity 2 Score: " . $row['activity_2_score'] . "<br>";
            echo "Prelim Grade: " . $row['prelim_grade'] . "<br>";
            echo "Midterm Grade: " . $row['midterm_grade'] . "<br>";
            echo "Final Grade: " . $row['final_grade'] . "<br>";
            echo "Overall Grade: " . $row['overall_grade'] . "<br>";
        }
    } else {
        echo "No grades found for this course.";
    }
} else {
    echo "Error executing query: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Grades</title>
    <style>
        body {
            background-color: #fff; /* White background */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            color: #333; /* Dark gray text */
        }

        .container {
            padding: 50px;
        }

        h1 {
            color: #333; /* Dark gray text */
            font-size: 3em;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #C8A2C8; /* Lilac header */
            color: #fff; /* White text */
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #C8A2C8; /* Lilac button background */
            color: #fff; /* White text */
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #BB8FBF; /* Darker lilac on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Grades for Course</h1>

    <?php
    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Display the grades in a table
        echo "<table>";
        echo "<tr>
                <th>Quiz 1</th>
                <th>Quiz 2</th>
                <th>Quiz 3</th>
                <th>Activity 1</th>
                <th>Activity 2</th>
                <th>Prelim Grade</th>
                <th>Midterm Grade</th>
                <th>Final Grade</th>
                <th>Overall Grade</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['quiz_1_score']) . "</td>
                    <td>" . htmlspecialchars($row['quiz_2_score']) . "</td>
                    <td>" . htmlspecialchars($row['quiz_3_score']) . "</td>
                    <td>" . htmlspecialchars($row['activity_1_score']) . "</td>
                    <td>" . htmlspecialchars($row['activity_2_score']) . "</td>
                    <td>" . htmlspecialchars($row['prelim_grade']) . "</td>
                    <td>" . htmlspecialchars($row['midterm_grade']) . "</td>
                    <td>" . htmlspecialchars($row['final_grade']) . "</td>
                    <td>" . htmlspecialchars($row['overall_grade']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No grades found for this course.</p>";
    }

    // Close the statement
    $stmt->close();
    ?>

    <a href="studentpg.php">Back to Dashboard</a>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>