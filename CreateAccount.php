<?php
include('connection_db.php'); // Include your database connection file

// Get the current year and month
$year = date('y'); // Last two digits of the year (e.g., 25 for 2025)
$month = date('m'); // Numeric month (e.g., 01 for January)
$accountids = "";
$password = "";

// Function to generate a random 6-character password
function generateRandomPassword($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $password;
}

// Function to check if the generated password exists in the database
function passwordExists($conn, $password) {
    $sql = "SELECT COUNT(*) FROM loginaccount WHERE password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $password);
    $stmt->execute();
    
    // Initialize the variable $count to avoid any warnings
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $middleInitial = $_POST['middle_initial'];
    $surname = $_POST['surname'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $bloodType = $_POST['blood_type'];
    $civilStatus = $_POST['civil_status'];
    $religion = $_POST['religion'];
    $motherName = $_POST['mother_name'];
    $motherContact = $_POST['mother_contact'];
    $fatherName = $_POST['father_name'];
    $fatherContact = $_POST['father_contact'];
    $studentContact = $_POST['student_contact'];
    $gmail = $_POST['gmail'];
    $homeAddress = $_POST['home_address'];
    $presentAddress = $_POST['present_address'];
    $yearLevel = $_POST['year_level'];
    $semester = $_POST['semester'];
    
    // Generate a unique password
    do {
        $password = generateRandomPassword(6); // Generate a 6-character password
    } while (passwordExists($conn, $password)); // Check if password exists in database

    try {
        // Generate Student ID
        
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM enrollment");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $enrollmentNumber = $row['total'] + 1; // Add 1 for the new student
        $enrollmentNumberFormatted = str_pad($enrollmentNumber, 2, '0', STR_PAD_LEFT);
        // Modify the logic for generating the studentID
        $type = "student";
        if ($type == "instructor") {
            // Generate Instructor ID by adding "I" in front of the ID
            $accountids = "I" . "{$year}-{$month}" . str_pad($enrollmentNumber, 2, '0', STR_PAD_LEFT);
        } else {
            // Generate Student ID as usual
            $accountids = "{$year}-{$month}" . str_pad($enrollmentNumber, 2, '0', STR_PAD_LEFT);
        }

        // Insert into personaldata table
        $stmt = $conn->prepare("INSERT INTO personaldata (AccountID, first_name, middle_initial, surname, birthdate, age, blood_type, civil_status, religion, mother_name, mother_contact, father_name, father_contact, student_contact, gmail, home_address, present_address, userType) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssssss", $accountids, $firstName, $middleInitial, $surname, $birthdate, $age, $bloodType, $civilStatus, $religion, $motherName, $motherContact, $fatherName, $fatherContact, $studentContact, $gmail, $homeAddress, $presentAddress, $type);
        if ($stmt->execute()) {
            // If the insertion into personaldata is successful, proceed with other operations.
            $stmt->close(); // Close after execution

            // Insert into enrollment table
            $stmt = $conn->prepare("INSERT INTO enrollment (AccountID, yearlevel, semester) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $accountids, $yearLevel, $semester);

            if ($stmt->execute()) {
                $stmt->close(); // Close after execution

                // Insert into loginaccount table
                $stmt = $conn->prepare("INSERT INTO loginaccount (AccountID, password, UserType) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $accountids, $password, $type);

                if ($stmt->execute()) {
                    $stmt->close(); // Close after execution

                    // Retrieve all subjects for the given year level
                    $stmt = $conn->prepare(
                        "SELECT s.code, s.units, s.InstructorID, p.first_name, p.middle_initial, p.surname 
                        FROM subject s 
                        JOIN personaldata p ON s.InstructorID = p.AccountID 
                        WHERE yearlevel = ? AND semester = ?"
                    );

                    $stmt->bind_param("is", $yearLevel, $semester);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($code, $units, $instructorID, $first_name, $middle_initial, $surname);

                    while ($stmt->fetch()) {
                        $instructorname = $first_name . " " . $middle_initial . " " . $surname;
                        $insertStmt = $conn->prepare("INSERT INTO grade (AccountID, InstructorID, instructorName, code, units) VALUES (?, ?, ?, ?, ?)");

                        if ($insertStmt === false) {
                            die('Error preparing insertion statement: ' . $conn->error);
                        }

                        $insertStmt->bind_param("sssss", $accountids, $instructorID, $instructorname, $code, $units);
                        if ($insertStmt->execute() === false) {
                            die('Error executing insertion statement: ' . $insertStmt->error);
                        }

                        $insertStmt->close();
                    }
                    $stmt->close(); // Close the main subject query statement
                } else {
                    echo "Error inserting into loginaccount: " . $stmt->error;
                }
            } else {
                echo "Error inserting into enrollment: " . $stmt->error;
            }
        } else {
            echo "Error inserting into personaldata: " . $stmt->error;
        }


        $_SESSION['accountid'] = $accountids;
        $_SESSION['password'] = $password;

        echo "<input type='hidden' id='generatedStudentId' value='$accountids'>";
        echo "<input type='hidden' id='generatedPassword' value='$password'>";
        echo "<script>document.getElementById('popup-modal').classList.remove('hidden');</script>";
        echo '

        <div class="flex justify-center items-center"> 
            <div id="popup-modal" tabindex="-1" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Please save your id and password somewhere</h3>
                            <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400">Student ID: '.$accountids.' </h3>
                            <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400">Password: '.$password.' </h3>
                            <button id="okButton" data-modal-hide="popup-modal" type="button" class="text-white bg-green-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    Ok
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Redirect to login.php when the Ok button is clicked
            document.getElementById("okButton").addEventListener("click", function() {
                window.location.href = "login.php"; // Redirect to login.php
            });
        </script>
        ';
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />    
    <title>Acccount Creation</title>
</head>
<body>
    <div class="text-center mt-10 ext-sm font-medium text-black">Fill up the enrollment form</div>
    <div class="flex justify-center">
        <form method="POST" action="" id="enrollmentForm">
            <div class="items-center justify-center flex flex-wrap bg-gray-700" style="width: 1200px; border-radius: 10px;">
                <!-- #region First Div-->
                <div class="m-4 w-96 items-center bg-gray-700 p-8" style="border-radius: 10px;">
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white regqui">First Name</label>
                    <input type="text" name="first_name" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Middle Initial</label>
                    <input type="text" name="middle_initial" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Surname</label>
                    <input type="text" name="surname" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Birthdate</label>
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input id="birthdate" name="birthdate" required datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                    </div>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Age</label>
                    <input type="text" name="age" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Blood Type</label>
                    <input type="text" name="blood_type" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>

                
                </div>
                    <div> <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Civil Status</label>
                    <input type="text" name="civil_status" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                   
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Religion</label>
                    <input type="text" name="religion" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Mother's Maiden Name</label>
                    <input type="text" name="mother_name" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Contact No#</label>
                    <input type="text" name="mother_contact" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Father's Name</label>
                    <input type="text" name="father_name" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Contact No#</label>
                    <input type="text" name="father_contact" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
               
                </div>
                <!-- endregion -->
                <!-- #region Second Div -->
                <div class="m-4 w-96 justify-center bg-gray-700 p-8" style="border-radius: 10px;">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Your Contact No#</label>
                    <input type="text" name="student_contact" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" placeholder="name@gmail.com" class="block mb-2 text-sm font-medium text-gray-900 text-white">Gmail</label>
                    <input type="text" name="gmail" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Home Address</label>
                    <input type="text" name="home_address" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required> 
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Present Address</label>
                    <input type="text" name="present_address" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                 
                    <!-- #region year menu -->
                    <button id="dropdownDefaultButton2" data-dropdown-toggle="dropdown2" class="mt-5 text-black bg-gray-50 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-700" type="button">Year Level <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                    </button>

                    <div id="dropdown2" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton2">
                            <li>
                            <a href="#"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-value="1">1st Year</a>
                            </li>
                            <li>
                            <a href="#"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-value="2">2nd Year</a>
                            </li>
                            <li>
                            <a href="#"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-value="3">3rd Year</a>
                            </li>
                            <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-value="4">4th Year</a>
                            </li>
                        </ul>
                    </div>
                    <!-- endregion -->
                     <!-- #region year menu -->
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="mt-5 text-black bg-gray-50 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-700" type="button">Semester <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                    </button>

                    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton2">
                            <li>
                            <a href="#"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-value="1st Semester">1st Semester</a>
                            </li>
                            <li>
                            <a href="#"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" data-value="2nd Semester">2nd Semester</a>
                            </li>
                        </ul>
                    </div>
                    <!-- endregion -->
                <input type="hidden" name="year_level" id="year-level">
                <input type="hidden" name="semester" id="semester">

                <!-- <input type="hidden" name="type" id="type"> -->

                <button id="enrollButton" type="submit"  data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="mt-6 block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Enroll 
                </button>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="dropdown.js" defer></script>
    <script>
        document.querySelectorAll('[data-value]').forEach((item) => {
        item.addEventListener('click', (e) => {
            const dropdownType = e.target.closest('[id^="dropdown"]').id; 
            const selectedValue = e.target.getAttribute('data-value');
            if (dropdownType === "dropdown2") {
                document.getElementById('year-level').value = selectedValue;
            } else {
                document.getElementById('semester').value =selectedValue
            }
        });
    });
    </script>
</body>
</html>