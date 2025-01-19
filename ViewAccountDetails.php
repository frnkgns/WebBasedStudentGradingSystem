<?php
    session_start();
    include('connection_db.php');

    $accountID = $_SESSION['accountID'];

    $smt = $conn->prepare("SELECT * FROM personaldata WHERE AccountID = ?");
    $smt->bind_param("s", $accountID);
    $smt->execute();
    $result = $smt->get_result();
    $Data = $result->fetch_assoc();
    $smt->close();
    
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

        $stmt = $conn->prepare(
            "UPDATE personaldata 
            SET first_name = ?, 
                middle_initial = ?, 
                surname = ?, 
                birthdate = ?, 
                age = ?, 
                blood_type = ?, 
                civil_status = ?, 
                religion = ?, 
                mother_name = ?, 
                mother_contact = ?, 
                father_name = ?, 
                father_contact = ?, 
                student_contact = ?, 
                gmail = ?, 
                home_address = ?, 
                present_address = ? 
            WHERE AccountId = ?"
        );
    
        // Bind parameters
        $stmt->bind_param(
            "ssssissssssssssss",
            $firstName,
            $middleInitial,
            $surname,
            $birthdate,
            $age,
            $bloodType,
            $civilStatus,
            $religion,
            $motherName,
            $motherContact,
            $fatherName,
            $fatherContact,
            $studentContact,
            $gmail,
            $homeAddress,
            $presentAddress,
            $accountID
        );
    
        if ($stmt->execute()) {
            // echo "Record updated successfully.";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    
        $stmt->close();
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
                <button id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName" class="flex items-center text-sm pe-1 font-medium text-gray-900 rounded-full hover:text-blue-600 dark:hover:text-blue-500 md:me-0 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-white" type="button">
                    <span class="sr-only">Open user menu</span>
                    <img src="images/isuccsict.png" class="h-8" alt="Flowbite Logo" />
                    <?php echo "Account Details: " . $accountID?>
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownAvatarName" class="ng-gray-200 z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownInformdropdownAvatarNameButtonationButton">
                        <li>
                            <?php
                                $userType = isset($Data['userType']) ? htmlspecialchars($Data['userType'], ENT_QUOTES, 'UTF-8') : 'guest';
                                $page = ($userType == 'student') ? 'studentpg.php' : 'professorpg.php';
                                echo "<a href='{$page}' class='block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white'>Grade</a>";
                            ?>
                        </li>
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
                    <input value="<?php echo $Data['first_name']?>" type="text" name="first_name" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Middle Initial</label>
                    <input value="<?php echo $Data['middle_initial']?>" type="text" name="middle_initial" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Surname</label>
                    <input value="<?php echo $Data['surname']?>" type="text" name="surname" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Birthdate</label>
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input value="<?php echo $Data['birthdate']?>" id="birthdate" name="birthdate" required datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                    </div>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Age</label>
                    <input value="<?php echo $Data['age']?>"type="text" name="age" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Blood Type</label>
                    <input value="<?php echo $Data['blood_type']?>"type="text" name="blood_type" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>

                
                </div>
                    <div> <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Civil Status</label>
                    <input value="<?php echo $Data['civil_status']?>"type="text" name="civil_status" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                   
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Religion</label>
                    <input value="<?php echo $Data['religion']?>"type="text" name="religion" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Mother's Maiden Name</label>
                    <input value="<?php echo $Data['mother_name']?>"type="text" name="mother_name" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Contact No#</label>
                    <input value="<?php echo $Data['mother_contact']?>"type="text" name="mother_contact" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Father's Name</label>
                    <input value="<?php echo $Data['father_name']?>"type="text" name="father_name" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Contact No#</label>
                    <input value="<?php echo $Data['father_contact']?>"type="text" name="father_contact" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
               
                </div>
                <!-- endregion -->
                <!-- #region Second Div -->
                <div class="m-4 w-96 justify-center bg-gray-700 p-8" style="border-radius: 10px;">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Your Contact No#</label>
                    <input value="<?php echo $Data['student_contact']?>"type="text" name="student_contact" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" placeholder="name@gmail.com" class="block mb-2 text-sm font-medium text-gray-900 text-white">Gmail</label>
                    <input value="<?php echo $Data['gmail']?>"type="text" name="gmail" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Home Address</label>
                    <input value="<?php echo $Data['home_address']?>"type="text" name="home_address" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required> 
                    <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 text-white">Present Address</label>
                    <input value="<?php echo $Data['present_address']?>"type="text" name="present_address" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>

                <!-- <input type="hidden" name="type" id="type"> -->

                <button id="enrollButton" type="submit"  data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="mt-6 block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Save 
                </button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="dropdown.js" defer></script>
</body>
</html>