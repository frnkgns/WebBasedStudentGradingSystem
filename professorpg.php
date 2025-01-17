<?php
    session_start();
    include('connection_db.php');

    $professorID = $_SESSION['accountID'];    
    $stmt = $conn->prepare("SELECT instructorName FROM grade
    WHERE InstructorID = ?");
    $stmt->bind_param("s", $professorID); 
    $stmt->execute();
    $result = $stmt->get_result();
    $Data = $result->fetch_assoc();
    $instructorname = $Data['instructorName'];
    $stmt->close();

    $stmt = $conn->prepare("SELECT g.instructorName, g.AccountID, g.quiz1, g.quiz2, g.quiz3, g.prelim, g.midterm, g.finals, p.first_name, p.middle_initial, p.surname 
    FROM grade g 
    JOIN personaldata p ON g.AccountID = p.AccountID 
    WHERE g.InstructorID = ?");

    $stmt->bind_param("s", $professorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
</head>
<body>
    <div class="flex bg-gray-400 p-4">
        <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col items-start pb-10 p-4">
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white"><?php echo $instructorname?></h5>
                <span class="text-sm text-gray-500 dark:text-gray-400"></span>
                <div class="mt-4 md:mt-6" >
                    <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enrollment</a>
                    <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ml-2">Curriculum</a>
                    <a href="logout.php" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ml-2">Logout</a>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto ml-6" id="viewgrades">
            <form method="POST" action="SaveGrades.php">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
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
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
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
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>