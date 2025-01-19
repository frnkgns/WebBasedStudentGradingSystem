<?php
session_start();
include('connection_db.php'); // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountid = $_POST['accountID'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM loginaccount WHERE AccountId = ? AND password = ?");
    $stmt->bind_param("ss", $accountid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['UserType'] = $user['UserType'];
        if ($user['UserType'] == 'student') {
            $_SESSION['accountID'] = $accountid;
            header("Location: studentpg.php");

        } else {
            $_SESSION['accountID'] = $accountid;
            header("Location: professorpg.php");
        }

        $stmt->close();

        $stmt = $conn->prepare("SELECT email FROM personaldata WHERE AccountId = ?");
        $stmt->bind_param("s", $accountid);
        $stmt->execute();
        $result = $stmt->get_result();

        $_SESSION['email'] = $result[0];

        exit();
    } else {
        echo "<div class='error'>Invalid username or password.</div>";
    }




}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
</head>
<body class="bg-gray-700 flex justify-center items-center h-screen">
    <div class="bg-white w-96 rounded-lg flex items-center justify-center" id="container">
        <div class="justify-center w-full max-w-sm p-4 md:p-8 ">
            <div class="flex justify-center">
                <img class="w-24 h-24 rounded-full shadow-lg" src="images/ccsictlogo.png" alt="CCSICT"/>
                <p class="tracking-wider text-gray-500 md:text-lg dark:text-gray-400"></p>
            </div>
            <form method="POST" class="space-y-6" action="">
                <div>
                    <label for="accountID" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Account ID</label>
                    <input type="text" name="accountID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="25-016432" required />
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                </div>
                <div>
                    <a href="SendREsetPassword.php" class="ms-auto text-sm text-blue-700 hover:underline dark:text-blue-500">Forgot password?</a>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Log in</button>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                    Not Enrolled yet? <a href="CreateAccount.php" class="text-blue-700 hover:underline dark:text-blue-500">Enroll Now!</a>
                </div>
            </form>
        </div>
    </div>
    <script src="login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
