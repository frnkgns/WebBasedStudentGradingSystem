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
        exit();
    } else {
        echo "<div class='error'>Invalid username or password.</div>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />

    <!-- #region CSS Styling -->
    <style>
        /* Embedded CSS for styling the login page */
        body {
            background-color: #fff; /* White background */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            color: #333; /* Dark gray text */
        }

        .container {
            padding: 50px;
        }

        h2 {
            color: #333; /* Dark gray text */
            font-size: 3em;
        }

        form {
            display: inline-block;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #666; /* Medium gray text */
            font-size: 1.2em;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        input[type="submit"] {
            background-color: #C8A2C8; /* Lilac button background */
            color: #fff; /* White text */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        input[type="submit"]:hover {
            background-color: #BB8FBF; /* Darker lilac on hover */
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
    <!-- endregion -->
</head>
<body>
    <div class="w-screen h-screen flex items-center justify-center" id="container">
        <div class="justify-center w-full max-w-sm p-4 md:p-8 ">
            <div class="flex justify-center">
                <img class="w-24 h-24 rounded-full shadow-lg" src="images/ccsictlogo.png" alt="Bonnie image"/>
                <p class="tracking-wider text-gray-500 md:text-lg dark:text-gray-400"></p>
            </div>
            <form method="POST" class="space-y-6" action="">
                <div>
                    <label for="accountID" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                    <input type="text" name="accountID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="John_0214" required />
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                </div>
                <div>
                    <a href="#" class="ms-auto text-sm text-blue-700 hover:underline dark:text-blue-500">Forgot password?</a>
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
