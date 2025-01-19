<?php
include('connection_db.php');

$year = date('y');
$month = date('m');
$accountids = "";
$password = "";

//para sa password random 6 digit 
function generateRandomPassword($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $password;
}

function passwordExists($conn, $password) {
    $sql = "SELECT COUNT(*) FROM loginaccount WHERE password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $password);
    $stmt->execute();
    
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
    
    // Generate a unique password
    do {
        $password = generateRandomPassword(6); // Generate a 6-character password
    } while (passwordExists($conn, $password)); // Check if password exists in database

    try {
        $type = "student";
        $maxRetries = 5;
        $retryCount = 0;
    
        do {
            // Generate a potential unique AccountID
            $randomNumber = rand(1000, 9999); // 4-digit random number
            $accountids = "{$year}-{$month}-" . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
    
            // Check if AccountID already exists in enrollment table
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM enrollment WHERE AccountID = ?");
            $stmt->bind_param("s", $accountids);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
    
            $retryCount++;
            if ($retryCount >= $maxRetries) {
                die("Error: Unable to generate a unique AccountID after multiple attempts.");
            }
        } while ($row['count'] > 0); // Retry if AccountID exists in enrollment
    
        // Insert into personaldata table
        $stmt = $conn->prepare("INSERT INTO personaldata (AccountID, first_name, middle_initial, surname, birthdate, age, blood_type, civil_status, religion, mother_name, mother_contact, father_name, father_contact, student_contact, gmail, home_address, present_address, userType) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssssss", $accountids, $firstName, $middleInitial, $surname, $birthdate, $age, $bloodType, $civilStatus, $religion, $motherName, $motherContact, $fatherName, $fatherContact, $studentContact, $gmail, $homeAddress, $presentAddress, $type);
        
        if ($stmt->execute()) {
            $stmt->close();
    
            // Insert into enrollment table
            $stmt = $conn->prepare("INSERT INTO enrollment (AccountID) VALUES (?)");
            $stmt->bind_param("s", $accountids);
            if ($stmt->execute()) {
                $stmt->close();
    
                // Insert into loginaccount table
                $stmt = $conn->prepare("INSERT INTO loginaccount (AccountID, password, UserType) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $accountids, $password, $type);
                if ($stmt->execute()) {
                    $stmt->close();
        
                        $stmt = $conn->prepare(
                            "SELECT s.code, s.units, s.InstructorID, p.first_name, p.middle_initial, p.surname 
                            FROM subject s 
                            JOIN personaldata p ON s.InstructorID = p.AccountID"
                        );
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
                        $stmt->close();

                        $_SESSION['accountid'] = $accountids;
                        $_SESSION['password'] = $password;
                    
                        echo "<input type='hidden' id='generatedStudentId' value='$accountids'>";
                        echo "<input type='hidden' id='generatedPassword' value='$password'>";
                        echo "<script>document.getElementById('popup-modal').classList.remove('hidden');</script>";
                    
                        echo '
                            <div class="fixed inset-0 flex justify-center items-center z-50 bg-black bg-opacity-50">
                                <div id="popup-modal" tabindex="-1" class="w-full max-w-md p-4 bg-white rounded-lg shadow-md">
                                    <div class="relative">
                                        <div class="p-4 text-center">
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
                    
                        <script>
                            document.getElementById("okButton").addEventListener("click", function() {
                                window.location.href = "login.php";
                            });
                        </script>
                        ';
                    
                } else {
                    echo "Error inserting into loginaccount: " . $stmt->error;
                }
            } else {
                echo "Error inserting into enrollment: " . $stmt->error;
            }
        } else {
            echo "Error inserting into personaldata: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    catch (Exception $e) {
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
<body class="bg-gray-700">
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="images/isuccsict.png" class="h-8" alt="Flowbite Logo" />
                    <p class="font-semibold text-black items-center flex h-full">Enrollment Form</p>
                </a>
                <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                    <p class="text-black items-center flex h-full">Isabela State University - Cauayan Campus : College of Computing Studies in Communication Technology</p>
                    </li>
                    <li>
                    <a href="login.php" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 ml-2">Login</a>
                    </li>
                </ul>
                </div>
            </div>
    </nav>

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

                <!-- <input type="hidden" name="type" id="type"> -->

                <button id="enrollButton" type="submit"  data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="mt-6 block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Enroll 
                </button>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="dropdown.js" defer></script>
    <!-- <script>
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
    </script> -->
</body>
</html>