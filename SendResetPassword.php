<?php
// Include PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection (update with your own credentials)
$conn = new mysqli("localhost", "root", "", "ODBC_db");
$showModal = false; 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $result = $conn->query("SELECT * FROM personaldata WHERE gmail='$email'");
    $Data = $result->fetch_assoc();
    $accountsID = $Data['AccountID'];
    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(12));
        $url = "localhost/WebBasedStudentGradingSystem/ResetPassword.php?token=$token";

        $conn->query("UPDATE loginaccount SET token ='$token', token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE AccountID = '$accountsID'");
        $mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aguinaldogwyneth69@gmail.com'; // Your Gmail address
            $mail->Password   = 'pzrj fnik gzzi xkzo';    // Your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email content
            $mail->setFrom('aguinaldogwyneth69@gmail.com', 'GradingSystem');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <p>We received a request to reset your password. Click the link below to reset it:</p>
                <a href='$url'>$url</a>
                <p>If you didn't request this, please ignore this email.</p>
            ";

            $mail->send();
            $showModal = true; 
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />

</head>
<body class="bg-gray-700">
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col items-center pb-10 mt-6">
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">Reset Password Request</h5>
                    <form class="max-w-sm mx-auto" method="POST">
                    <label for="email-address-icon" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                            <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
                            <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
                        </svg>
                        </div>
                        <input type="text" name="email" id="email-address-icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com">
                    </div>
                    <div class="flex mt-4 md:mt-6 justify-center">
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Send
                    </button>

                    <button onclick="window.location.href='login.php'" type="button" class="inline-flex items-center py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Login
                    </button>
                </div>
                </form>                
            </div>
        </div>
    </div>
    <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Request Sent</h3>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <p class="mt-4">Request was sent to your Gmail: <span id="gmailAddress"></span></p>
            <div class="mt-4">
                <button onclick="closeModal()" class="bg-blue-500 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>
        <?php if ($showModal): ?>
            window.onload = function() {
                document.getElementById('gmailAddress').textContent = "<?php echo $email; ?>";
                document.getElementById('successModal').classList.remove('hidden'); // Show the modal
            };
        <?php endif; ?>

        function closeModal() {
            document.getElementById('successModal').classList.add('hidden');
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>
