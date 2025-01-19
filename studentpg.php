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
    $overallGWA = '';

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
        WHERE AccountID = ?
        ORDER BY s.yearlevel");

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
<body class="bg-gray-700">
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <button id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName" class="flex items-center text-sm pe-1 font-medium text-gray-900 rounded-full hover:text-blue-600 dark:hover:text-blue-500 md:me-0 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-white" type="button">
                <span class="sr-only">Open user menu</span>
                <img src="images/isuccsict.png" class="h-8" alt="Flowbite Logo" />
                <?php echo $UserName . ": " . $accountID?>
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>

                <!-- Dropdown menu -->
                <div id="dropdownAvatarName" class="ng-gray-200 z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownInformdropdownAvatarNameButtonationButton">
                    <li>
                        <a href="ViewAccountDetails.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Account Details</a>
                    </li>
                    </ul>
                    <div class="py-2">
                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
                    </div>
                </div>
                
                <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    
                    <li>
                    <a>Isabela State University - College of Computing Studies in Information Communication Technology</a>
                    </li>
                </ul>
                </div>
            </div>
    </nav>
    <div>
        <p> <?php echo $overallGWA ?> </p>
    </div>
    <div class="flex bg-gray-700 p-4">
        <div class="relative overflow-x-auto ml-6 rounded-lg" id="viewgrades">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="bg-gray-200">
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
                    $totalWeightedSum = 0;
                    $totalWeight = 0;

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

                        // Assign percentage weights to each activity
                        $quizWeight = 0.15; 
                        $prelimWeight = 0.20;
                        $midtermWeight = 0.30;
                        $finalsWeight = 0.35;

                        // // Calculate weighted average for each subject
                        // echo "$row[quiz1] * $quizWeight <br> ";
                        // echo "$row[quiz2] * $quizWeight   <br>";
                        // echo "$row[quiz3] * $quizWeight   <br>";
                        // echo "$row[prelim] * $prelimWeight   <br>";
                        // echo "$row[midterm] * $midtermWeight   <br>";
                        // echo "$row[finals] * $finalsWeight   <br>";

                        $weightedTotal = 
                            ($row['quiz1'] * $quizWeight) + 
                            ($row['quiz2'] * $quizWeight) + 
                            ($row['quiz3'] * $quizWeight) + 
                            ($row['prelim'] * $prelimWeight) + 
                            ($row['midterm'] * $midtermWeight) + 
                            ($row['finals'] * $finalsWeight);

                        $totalWeightedSum += $weightedTotal;
                        $totalWeight += ($quizWeight + $prelimWeight + $midtermWeight + $finalsWeight);
                        $average = $weightedTotal;
                        echo "<td class='px-6 py-4'>" . htmlspecialchars($average) . "</td>";
                        echo "</tr>";

                        // echo "Total: $totalWeightedSum += $weightedTotal <br><br>";
                        // echo "$totalWeight += ($quizWeight + $prelimWeight + $midtermWeight + $finalsWeight) <br><br>";
                        // echo "Average: $average = $weightedTotal<br><br>";

                    }

                    // After the loop, calculate the overall GWA (General Weighted Average)
                    if ($totalWeight > 0) {
                        $overallGWA = $totalWeightedSum / $totalWeight;
                        echo "<div class='bg-white px-6 py-4'>General Weighted Average: " . number_format($overallGWA, 2) . "</div>";
                        // echo "GWA: $overallGWA '=' $totalWeightedSum '/' $totalWeight";
                    } else {
                        echo "<div class='px-6 py-4'>No grades available to calculate GWA.</div>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>