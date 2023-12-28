<?php
include('Config.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $emailAddress = $_POST["emailAddress"];
    $gender = $_POST["gender"];
    $favouriteColours = implode(", ", $_POST["favouriteColours"]);
    $password = $_POST["password"];
    $retypePassword = $_POST["retypePassword"];

    if ($password != $retypePassword) {
        echo "Error: Passwords do not match. Please re-enter.";
        // You might want to redirect the user or take other actions here.
        // For example, you could use header("Location: error_page.php") to redirect to an error page.
        // Make sure to exit or die after redirection to prevent further code execution.
        exit;
    }
    $hashedPassword = hash('sha256', $password);
    $stmt = $conn->prepare("INSERT INTO users (UserName, FirstName, LastName, EmailAddress, Gender, FavouriteColours, Password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $firstName, $lastName, $emailAddress, $gender, $favouriteColours, $hashedPassword);
    
    
    if ($stmt->execute()) {
        header("Location: login.php?registration=success");
        exit();
    } else {
        $error = "Registration failed. Please try again.";
        header("Location: login.php?registration=failed&error=" . urlencode($error));
        exit();
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
    <title>User Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="registration-container">
        <h2>User Registration</h2>
        <?php
        if (isset($error)) {
            echo "<p class='text-danger'>$error</p>";
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="firstName" class="form-label">First Name:</label>
                <input type="text" id="firstName" name="firstName" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="lastName" class="form-label">Last Name:</label>
                <input type="text" id="lastName" name="lastName" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="emailAddress" class="form-label">Email Address:</label>
                <input type="email" id="emailAddress" name="emailAddress" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="gender" class="form-label">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label mt-3">Favourite Colours:</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="favouriteColours[]" value="Yellow"> Yellow
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="favouriteColours[]" value="Orange"> Orange
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="favouriteColours[]" value="Brown"> Brown
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="retypePassword" class="form-label">Re-type Password:</label>
                <input type="password" id="retypePassword" name="retypePassword" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success mt-3">Register</button>
        </form>

        <p class="mt-3">Already registered? <a href="login.php">Login here</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>