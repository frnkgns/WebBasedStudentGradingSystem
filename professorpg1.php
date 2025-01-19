<?php
    session_start();
    include('connection_db.php');

    $professorID = $_SESSION['accountID'];    
    $stmt = $conn->prepare("SELECT instructorName, InstructorID FROM grade
    WHERE InstructorID = ?");
    $stmt->bind_param("s", $professorID); 
    $stmt->execute();
    $result = $stmt->get_result();
    $Data = $result->fetch_assoc();
    $instructorname = $Data['instructorName'];
    $instructorid = $Data['InstructorID'];
    $stmt->close();

    $yearlevels = ['1', '2', '3', '4'];
    $professorYearLevels = [];
    foreach ($yearlevels as $index => $yearlevel) {
        $stmt = $conn->prepare("
            SELECT s.subjectname, s.yearlevel, g.code, g.instructorName, g.AccountID, g.quiz1, g.quiz2, g.quiz3, 
                   g.prelim, g.midterm, g.finals, p.first_name, p.middle_initial, p.surname 
            FROM grade g 
            LEFT JOIN personaldata p ON g.AccountID = p.AccountID 
            LEFT JOIN subject s ON g.code = s.code
            WHERE g.InstructorID = ? AND (s.yearlevel = ? OR s.yearlevel IS NULL)
        ");
    
        if ($stmt) { 
            $stmt->bind_param("ss", $professorID, $yearlevel);
            $stmt->execute();
            ${"result" . ($index + 1)} = $stmt->get_result(); // Dynamically create $result1, $result2, etc.
    
            if (${"result" . ($index + 1)}->num_rows > 0) {
                $professorYearLevels[] = $yearlevel; // Add year level to the array if results exist
            }
    
            $stmt->close();
        } else {
            error_log("Query preparation failed for yearlevel: $yearlevel");
        }
    }

    $stmt = $conn->prepare("SELECT subjectname, code, yearlevel, semester FROM subject WHERE InstructorID = ?");
    $stmt->bind_param('s', $professorID);
    $stmt->execute();
    $subjects = $stmt->get_result();
    $stmt->close();

    $firstSubject = $subjects->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
</head>
<body class="bg-gray-700">
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <button id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName" class="flex items-center text-sm pe-1 font-medium text-gray-900 rounded-full hover:text-blue-600 dark:hover:text-blue-500 md:me-0 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-white" type="button">
                <span class="sr-only">Open user menu</span>
                <img src="images/isuccsict.png" class="h-8" alt="Flowbite Logo" />
                <?php echo $instructorname . ": " . $instructorname?>
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>

                <!-- Dropdown menu -->
                <div id="dropdownAvatarName" class="ng-gray-200 z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownInformdropdownAvatarNameButtonationButton">
                    <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Account Details</a>
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
                <li>
                <a href="logout.php" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ml-2">Logout</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="flex bg-gray-700 p-4">
        <div class="relative overflow-x-auto" id="viewgrades">
            <div class="bg-white p-4">
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Dropdown button <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                </div>
            </div>
            <form id="firstForm" method="POST" action="SaveGrades.php" class="ajax-form">
                <div class="rounded-lg bg-white">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr class="bg-gray-200">
                                        <th scope="col" class="px-6 py-3">Subject</th>
                                        <th scope="col" class="px-6 py-3">Student ID</th>
                                        <th scope="col" class="px-6 py-3">Name</th>
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
                                    while ($row = $result1->fetch_assoc()) {
                                        echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['subjectname']) . " (" . htmlspecialchars($row['code']) . ") ". "</td>";
                                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['AccountID']) . "</td>";
                                        echo "<td class='px-6 py-4'>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['middle_initial']) . " " . htmlspecialchars($row['surname']) ."</td>";

                                        echo "<input type='hidden' name='AccountID_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['AccountID']) . "'>";

                                        echo "<td class='px-6 py-4'><input type='text' name='quiz1_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz1']) . "' type='number' id='quiz1' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                        echo "<td class='px-6 py-4'><input type='text' name='quiz2_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz2']) . "' type='number' id='quiz2-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                        echo "<td class='px-6 py-4'><input type='text' name='quiz3_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz3']) . "' type='number' id='quiz3-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                        echo "<td class='px-6 py-4'><input type='text' name='prelim_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['prelim']) . "' type='number' id='prelim' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                        echo "<td class='px-6 py-4'><input type='text' name='midterm_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['midterm']) . "' type='number' id='midterm' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                        echo "<td class='px-6 py-4'><input type='text' name='finals_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['finals']) . "' type='number' id='finals' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                        
                                        $total = $row['quiz1'] + $row['quiz2'] + $row['quiz3'] + $row['prelim'] + $row['midterm'] + $row['finals'];
                                        $average = $total / 6;
                                        echo "<td class='px-6 py-4'>" . htmlspecialchars($average) . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save</button>
                            </div>             
                </div>
            </form>

            <form id="secondForm" method="POST" action="SaveGrades.php" class="ajax-form">
                <div class="rounded-lg bg-white">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="bg-gray-200">
                                <th scope="col" class="px-6 py-3">Subject</th>
                                <th scope="col" class="px-6 py-3">Student ID</th>
                                <th scope="col" class="px-6 py-3">Name</th>
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
                            while ($row = $result2->fetch_assoc()) {
                                echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['subjectname']) . " (" . htmlspecialchars($row['code']) . ") ". "</td>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['AccountID']) . "</td>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['middle_initial']) . " " . htmlspecialchars($row['surname']) ."</td>";

                                echo "<input type='hidden' name='AccountID_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['AccountID']) . "'>";

                                echo "<td class='px-6 py-4'><input type='text' name='quiz1_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz1']) . "' type='number' id='quiz1' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='quiz2_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz2']) . "' type='number' id='quiz2-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='quiz3_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz3']) . "' type='number' id='quiz3-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='prelim_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['prelim']) . "' type='number' id='prelim' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='midterm_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['midterm']) . "' type='number' id='midterm' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='finals_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['finals']) . "' type='number' id='finals' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                
                                $total = $row['quiz1'] + $row['quiz2'] + $row['quiz3'] + $row['prelim'] + $row['midterm'] + $row['finals'];
                                $average = $total / 6;
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($average) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save</button>
                    </div>
                </div>
            </form>

            <form id="thirdForm" method="POST" action="SaveGrades.php" class="ajax-form">
                <div class="rounded-lg bg-white">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="bg-gray-200">
                                <th scope="col" class="px-6 py-3">Subject</th>
                                <th scope="col" class="px-6 py-3">Student ID</th>
                                <th scope="col" class="px-6 py-3">Name</th>
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
                            while ($row = $result3->fetch_assoc()) {
                                echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['subjectname']) . " (" . htmlspecialchars($row['code']) . ") ". "</td>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['AccountID']) . "</td>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['middle_initial']) . " " . htmlspecialchars($row['surname']) ."</td>";

                                echo "<input type='hidden' name='AccountID_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['AccountID']) . "'>";

                                echo "<td class='px-6 py-4'><input type='text' name='quiz1_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz1']) . "' type='number' id='quiz1' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='quiz2_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz2']) . "' type='number' id='quiz2-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='quiz3_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz3']) . "' type='number' id='quiz3-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='prelim_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['prelim']) . "' type='number' id='prelim' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='midterm_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['midterm']) . "' type='number' id='midterm' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='finals_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['finals']) . "' type='number' id='finals' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                
                                $total = $row['quiz1'] + $row['quiz2'] + $row['quiz3'] + $row['prelim'] + $row['midterm'] + $row['finals'];
                                $average = $total / 6;
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($average) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save</button>
                    </div>

                </div>
            </form>

            <form id="fourthForm" method="POST" action="SaveGrades.php" class="ajax-form">
                <div class="rounded-lg bg-white">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="bg-gray-200">
                                <th scope="col" class="px-6 py-3">Subject</th>
                                <th scope="col" class="px-6 py-3">Student ID</th>
                                <th scope="col" class="px-6 py-3">Name</th>
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
                            while ($row = $result4->fetch_assoc()) {
                                echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['subjectname']) . " (" . htmlspecialchars($row['code']) . ") ". "</td>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['AccountID']) . "</td>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['middle_initial']) . " " . htmlspecialchars($row['surname']) ."</td>";

                                echo "<input type='hidden' name='AccountID_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['AccountID']) . "'>";

                                echo "<td class='px-6 py-4'><input type='text' name='quiz1_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz1']) . "' type='number' id='quiz1' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='quiz2_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz2']) . "' type='number' id='quiz2-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='quiz3_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['quiz3']) . "' type='number' id='quiz3-input' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='prelim_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['prelim']) . "' type='number' id='prelim' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='midterm_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['midterm']) . "' type='number' id='midterm' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                echo "<td class='px-6 py-4'><input type='text' name='finals_" . $row['AccountID'] . "' value='" . htmlspecialchars($row['finals']) . "' type='number' id='finals' aria-describedby='helper-text-explanation' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='80' required w-50'></td>";
                                
                                $total = $row['quiz1'] + $row['quiz2'] + $row['quiz3'] + $row['prelim'] + $row['midterm'] + $row['finals'];
                                $average = $total / 6;
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($average) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="subjectdropdown.js"></script>
    <script src="FetchSubjectData.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>var firstSubject = <?php echo json_encode($firstSubject); ?>;</script>
    <script>var professorYearLevels = <?php echo json_encode($professorYearLevels); ?>;</script>
</script>
</body>
</html>