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
        header("Location: UserManagement.php?registration=success");
        exit();
    } else {
        $error = "Registration failed. Please try again.";
        header("Location: UserManagement.php?registration=failed&error=" . urlencode($error));
        exit();
    }

    $stmt->close();
}

$conn->close();


?>
