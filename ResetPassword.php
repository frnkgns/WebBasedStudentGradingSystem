<?php
$conn = new mysqli("localhost", "root", "", "ODBC_db");
$accountIDS = '';
$showModal = false;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    if (isset($_GET['token'])) {
        $token = $conn->real_escape_string($_GET['token']);

        // Verify token
        $result = $conn->query("SELECT * FROM loginaccount WHERE token = '$token'");
        if ($result->num_rows > 0) {
            $Data = $result->fetch_assoc();
            $accountIDS = $Data['AccountID'];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $new_password = $_POST['password'];
                $confirm_newpass = $_POST['confirm_password'];
                if($new_password == $confirm_newpass){
                    $conn->query("UPDATE loginaccount SET password= '$new_password', token = NULL, token_expiry = NULL WHERE token ='$token'");
                    $showModal = true;

                }  else{
                    echo "
                    <div id='errorModal' class='fixed inset-0 flex justify-center items-center z-50'>
                        <div class='modal modal-open'>
                            <div class='modal-content bg-white p-6 rounded-lg shadow-md'>
                                <h3 class='text-xl font-semibold text-red-600'>Error</h3>
                                <p class='mt-4 text-gray-700'>Password do not match</p>
                                <div class='mt-6'>
                                    <button onclick='closeModal1()' class='inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'>
                                        Try again
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    function closeModal1() {
                        document.getElementById('errorModal').classList.add('hidden');
                    }
                    </script>
                    ";
                }
            }
        } else {
            echo "
            <div id='errorModal' class='fixed inset-0 flex justify-center items-center z-50'>
                <div class='modal modal-open'>
                    <div class='modal-content bg-white p-6 rounded-lg shadow-md'>
                        <h3 class='text-xl font-semibold text-red-600'>Error</h3>
                        <p class='mt-4 text-gray-700'>Invalid or expired token.</p>
                        <div class='mt-6'>
                            <button onclick='window.location.href=\"SendREsetPassword.php\"' class='inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'>
                                Request Password Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        echo "
        <div id='errorModal' class='fixed inset-0 flex justify-center items-center z-50'>
            <div class='modal modal-open'>
                <div class='modal-content bg-white p-6 rounded-lg shadow-md'>
                    <h3 class='text-xl font-semibold text-red-600'>Error</h3>
                    <p class='mt-4 text-gray-700'>Invalid or expired token.</p>
                    <div class='mt-6'>
                        <button onclick='window.location.href=\"login.php\"' class='inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'>
                            Go to Login
                        </button>
                    </div>
                </div>
            </div>
        </div>
        ";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
</head>
<body class="bg-gray-700">

<nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <button id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName" class="flex items-center text-sm pe-1 font-medium text-gray-900 rounded-full hover:text-blue-600 dark:hover:text-blue-500 md:me-0 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-white" type="button">
                <span class="sr-only">Open user menu</span>
                <img src="images/isuccsict.png" class="h-8" alt="Flowbite Logo" />
                <?php echo $accountIDS?>
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>

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

    <div class="items-center flex h-screen">
        <form method="POST" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
            <!-- New Password -->
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password:</label>
                <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required placeholder="Enter new password">
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm-password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required placeholder="Confirm new password">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full px-4 py-2 text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 text-sm font-medium">
                Reset Password
            </button>
        </form>
    </div>

    <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Password Reset Sucess!!</h3>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <p class="mt-4">You may now log in using your new password</p>
            <div class="mt-4">
                <button onclick="closeModal()" class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>
        <?php if ($showModal): ?>
            window.onload = function() {
                document.getElementById('successModal').classList.remove('hidden');
            };
        <?php endif; ?>

        function closeModal() {
            document.getElementById('successModal').classList.add('hidden');
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>