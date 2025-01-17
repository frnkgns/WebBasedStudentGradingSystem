<?php
session_start(); // Start the session to retrieve data

// Retrieve session data
$studentid = isset($_SESSION['studentid']) ? $_SESSION['studentid'] : '';
$password = isset($_SESSION['password']) ? $_SESSION['password'] : '';

// Optional: You may want to clear the session data after retrieval if it's not needed anymore
// unset($_SESSION['studentid'], $_SESSION['password']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Modal structure -->
    <div id="popup-modal" tabindex="-1" class="overflow-y-auto overflow-x-hidden fixed top-0 left-0 right-0 bottom-0 z-50 flex justify-center items-center w-full h-full <?php echo ($studentid && $password) ? '' : 'hidden'; ?>">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" id="close-modal-button">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400" id="modal-message">
                        <?php 
                            if ($studentid && $password) {
                                echo "Student ID: " . htmlspecialchars($studentid) . "<br>Password: " . htmlspecialchars($password);
                            } else {
                                echo "No data available";
                            }
                        ?>
                    </h3>
                    <button id="confirm-button" type="button" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Ok
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if the modal should be visible (this is for client-side control)
            const modal = document.getElementById('popup-modal');
            if (modal && !modal.classList.contains('hidden')) {
                // If modal is visible, allow closing
                document.getElementById('close-modal-button').addEventListener('click', function() {
                    modal.classList.add('hidden');
                });

                document.getElementById('confirm-button').addEventListener('click', function() {
                    modal.classList.add('hidden');
                });
            }
        });
    </script>

</body>
</html>
