<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

if (isset($_SESSION['username'])) {
    echo "<div style='position: absolute; top: 10px; right: 10px;'>";
    echo "Welcome, " . $_SESSION['username'] . " | ";
    echo "<a href='Logout.php'>Logout</a>";
    echo "</div>";
}





include('Config.php');
if (isset($_GET['Id'])) {
    $userIdToEdit = $_GET['Id'];
    $sqlSelectUser = "SELECT * FROM users WHERE Id = ?";
    $stmtSelectUser = $conn->prepare($sqlSelectUser);
    $stmtSelectUser->bind_param("i", $userIdToEdit);
    $stmtSelectUser->execute();
    $result = $stmtSelectUser->get_result();
    $userDetails = $result->fetch_assoc();
    $stmtSelectUser->close();
} else {
    echo "No user ID provided for editing.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updatedUsername = $_POST['username'];
    $updatedFirstName = $_POST['firstName'];
    $updatedLastName = $_POST['lastName'];
    $updatedEmailAddress = $_POST['emailAddress'];
    $updatedGender = $_POST['gender'];
    $updatedFavouriteColours = implode(", ", $_POST['favouriteColours']);
    $sqlUpdate = "UPDATE users SET UserName=?, FirstName=?, LastName=?, EmailAddress=?, Gender=?, FavouriteColours=? WHERE Id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssssssi", $updatedUsername, $updatedFirstName, $updatedLastName, $updatedEmailAddress, $updatedGender, $updatedFavouriteColours, $userIdToEdit);

    if ($stmtUpdate->execute()) {
        header("Location: UserManagement.php?update=success");
        exit();
    } else {
        // echo "Error updating user: " . $stmtUpdate->error;
        $error = "Registration failed. Please try again.";
        header("Location: UserManagement.php?update=failed&error=" . urlencode($error));
        exit();
    }

    $stmtUpdate->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Edit User</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?Id=" . $userIdToEdit; ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo $userDetails['UserName']; ?>" required>
        </div>
        <div class="form-group">
            <label for="firstName" class="form-label">First Name:</label>
            <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $userDetails['FirstName']; ?>" required>
        </div>
        <div class="form-group">
            <label for="lastName" class="form-label">Last Name:</label>
            <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $userDetails['LastName']; ?>" required>
        </div>
        <div class="form-group">
            <label for="emailAddress" class="form-label">Email Address:</label>
            <input type="email" id="emailAddress" name="emailAddress" class="form-control" value="<?php echo $userDetails['EmailAddress']; ?>" required>
        </div>
        <div class="form-group">
            <label for="gender" class="form-label">Gender:</label>
            <select id="gender" name="gender" class="form-control" required>
                <option value="Male" <?php echo ($userDetails['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($userDetails['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Favourite Colours:</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="favouriteColours[]" value="Yellow" <?php echo (strpos($userDetails['FavouriteColours'], 'Yellow') !== false) ? 'checked' : ''; ?>> Yellow
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="favouriteColours[]" value="Orange" <?php echo (strpos($userDetails['FavouriteColours'], 'Orange') !== false) ? 'checked' : ''; ?>> Orange
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="favouriteColours[]" value="Brown" <?php echo (strpos($userDetails['FavouriteColours'], 'Brown') !== false) ? 'checked' : ''; ?>> Brown
            </div>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
