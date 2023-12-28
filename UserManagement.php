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


if (isset($_GET['update'])) {
    if ($_GET['update'] === 'success') {
        $registrationMessage = "update successful.";
    } elseif ($_GET['update'] === 'failed') {
        $error = isset($_GET['error']) ? urldecode($_GET['error']) : "update failed. Please try again.";
    }
}

if (isset($updateMessage)) {
    echo "<p class='text-success'>$updateMessage</p>";
} elseif (isset($error)) {
    echo "<p class='text-danger'>$error</p>";
}







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
if (isset($_GET['delete'])) {
    $userIdToDelete = $_GET['delete'];
    $sqlDelete = "DELETE FROM users WHERE Id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $userIdToDelete);
    $stmtDelete->execute();
    $stmtDelete->close();
}
$sqlSelect = "SELECT * FROM users";
$result = $conn->query($sqlSelect);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">User Management</h2>
    <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</a>

    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>Gender</th>
                <th>Favourite Colours</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['UserName']}</td>";
                    echo "<td>{$row['FirstName']}</td>";
                    echo "<td>{$row['LastName']}</td>";
                    echo "<td>{$row['EmailAddress']}</td>";
                    echo "<td>{$row['Gender']}</td>";
                    echo "<td>{$row['FavouriteColours']}</td>";
                    echo "<td><a href='editUser.php?Id={$row['Id']}' class='btn btn-warning btn-sm' >Edit</a></td>";
                    echo "<td><a href='usermanagement.php?delete={$row['Id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="registration-container">
                        <form method="post" action="StoreUser.php">
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
                <label class="form-label">Favourite Colours:</label>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>