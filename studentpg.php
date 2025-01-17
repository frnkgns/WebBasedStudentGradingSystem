<?php
    session_start();
    include('connection_db.php');

    if (!isset($_SESSION['accountID'])) {
        echo "Session 'accountID' is not set!";
        exit();
    }
    $accountID = $_SESSION['accountID'];

    $smt = $conn->prepare("SELECT * FROM personaldata WHERE AccountID = ?");
    $smt->bind_param("s", $accountID);
    $smt->execute();
    $result = $smt->get_result();

    $Data = $result->fetch_assoc();
    $UserName = $Data['first_name']. " " . $Data['middle_initial'] . " " . $Data['surname'];
    $smt->close();

    $smt = $conn->prepare("SELECT * FROM enrollment WHERE AccountID = ?");
    $smt->bind_param("s", $accountID);
    $smt->execute();
    $result = $smt->get_result();

    $Data = $result->fetch_assoc();
    $Year = '';
    $Semester = $Data['semester'];

    switch ($Data['yearlevel']) {
        case '1':
            $Year = '1st Year';
            break;
        case '2':
            $Year = '2nd Year';
            break;
        case '3':
            $Year = '3rd Year';
            break;
        case '4':
            $Year = '4th Year';
            break;  
    }

    $Course = $Year . ": Computer Science - " . $Semester;
    $smt->close();

    $smt = $conn->prepare(
        "SELECT g.code, g.instructorName, g.quiz1, g.quiz2, g.quiz3, g.prelim, g.midterm, g.finals, s.subjectname, s.units 
        FROM grade g 
        JOIN subject s ON s.code = g.code
        WHERE AccountID = ?");

    $smt->bind_param("s", $accountID);
    $smt->execute();
    $result = $smt->get_result();
    $smt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    <title>Document</title>
</head>
<body>
    <div class="flex bg-gray-400 p-4">
        <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col items-start pb-10 p-4">
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white"><?php echo $UserName?></h5>
                <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo $Course?></span>
                <div class="flex mt-4 md:mt-6">
                    <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enrollment</a>
                    <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ml-2">Curricullum</a>
                    <a href="logout.php" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ml-2">Logout</a>


                    <!-- <a href="#" class="py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Message</a> -->
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto ml-6" id="viewgrades">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Subject Code</th>
                        <th scope="col" class="px-6 py-3">Subject Name</th>
                        <th scope="col" class="px-6 py-3">Units</th>
                        <th scope="col" class="px-6 py-3">Instructor</th>
                        <th scope="col" class="px-6 py-3">Quiz 1</th>
                        <th scope="col" class="px-6 py-3">Quiz 2</th>
                        <th scope="col" class="px-6 py-3">Quiz 3</th>
                        <th scope="col" class="px-6 py-3">Prelim</th>
                        <th scope="col" class="px-6 py-3">Midterm</th>
                        <th scope="col" class="px-6 py-3">Finals</th>
                        <th scope="col" class="px-6 py-3">Average</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row of the result set
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['code']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['subjectname']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['units']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['instructorName']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['quiz1']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['quiz2']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['quiz3']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['prelim']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['midterm']) . "</td>";
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['finals']) . "</td>";
                        
                        $total = $row['quiz1'] + $row['quiz2'] + $row['quiz3'] + $row['prelim'] + $row['midterm'] + $row['finals'];
                        $average = $total / 6;
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($average) . "</td>";
                        echo "</tr>";                    }
                    ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>