<?php
    include('connection_db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = $_POST['code'] ?? '';

    $stmt = $conn->prepare("
        SELECT g.AccountID, g.quiz1, g.quiz2, g.quiz3, g.prelim, g.midterm, g.finals, p.first_name, p.middle_initial, p.surname 
        FROM grade g
        JOIN personaldata p ON g.AccountID = p.AccountID
        WHERE g.code = ?
    ");

    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $grades = [];
    while ($row = $result->fetch_assoc()) {
        $total = $row['quiz1'] + $row['quiz2'] + $row['quiz3'] + $row['prelim'] + $row['midterm'] + $row['finals'];
        $average = $total / 6;
        $row['average'] = round($average, 2); // Add average to the array

        $grades[] = $row;
    }

    echo json_encode($grades);
}
?>
