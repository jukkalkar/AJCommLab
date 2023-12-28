<?php
session_start();
if (isset($_GET['registration'])) {
    if ($_GET['registration'] === 'success') {
        $registrationMessage = "Registration successful. You can now log in.";
    } elseif ($_GET['registration'] === 'failed') {
        $error = isset($_GET['error']) ? urldecode($_GET['error']) : "Registration failed. Please try again.";
    }
}

if (isset($registrationMessage)) {
    echo "<p class='text-success'>$registrationMessage</p>";
} elseif (isset($error)) {
    echo "<p class='text-danger'>$error</p>";
}




include('Config.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST["username"];
    $inputPassword = $_POST["password"];
    $hashedPassword = hash('sha256', $inputPassword);
    $stmt = $conn->prepare("SELECT * FROM users WHERE UserName = ? AND Password = ?");
    $stmt->bind_param("ss", $inputUsername, $hashedPassword);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        // Store user information in the session
        $_SESSION['username'] = $inputUsername;
        header("Location: UserManagement.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

</head>
<body>

<div class="container">
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($error)) {
            echo "<p class='text-danger'>$error</p>";
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Login</button>
        </form>

        <p>Not registered yet? <a href="UserRegistration.php">Register here</a></p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>

