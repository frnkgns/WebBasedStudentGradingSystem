<?php
session_start();
include('connection_db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $professorID = $_SESSION['accountID'];

    foreach ($_POST as $key => $value) {
        if (strpos($key, '_') !== false) {
            list($field, $studentID) = explode('_', $key);
        } else {
            continue;
        }

        // Convert value to an integer (sanitize input)
        $gradeValue = intval($value);

        // Allowed fields (to prevent SQL injection)
        $validFields = ['quiz1', 'quiz2', 'quiz3', 'prelim', 'midterm', 'finals'];
        if (in_array($field, $validFields)) {
            // Update the grade for the specific student
            $stmt = $conn->prepare("UPDATE grade SET $field = ? WHERE AccountID = ? AND InstructorID = ?");
            $stmt->bind_param("iss", $gradeValue, $studentID, $professorID);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redirect to prevent form resubmission
    header("Location: professorpg.php");
    exit();
}
?>
