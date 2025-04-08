<?php
session_start();
$conn = new mysqli("localhost", "root", "", "login_register");

$error = ''; // For error messages

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = trim($_POST["last_name"]);
    $first_name = trim($_POST["first_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $repeatPassword = trim($_POST['repeat_password']);

    // Validate inputs
    if (empty($last_name) || empty($first_name) || empty($email) || empty($password) || empty($repeatPassword)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $repeatPassword) {
        $error = "Passwords do not match.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (last_name, first_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $last_name, $first_name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful! <a href='login.php'>Login</a>";
            exit;
        } else {
            if ($conn->errno == 1062) { // Duplicate entry error
                $error = "An account with that email already exists.";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!-- HTML form for user registration -->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style1.css"> 
</head>
<body>    
    <form method="POST" action="register.php">
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <label>First Name:</label><br>
        <input type="text" name="first_name"><br>
        <label>Last Name:</label><br>
        <input type="text" name="last_name"><br>
        <label>Email:</label><br>
        <input type="email" name="email"><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br>
        <label>Repeat Password:</label><br>
        <input type="password" name="repeat_password"><br><br>
        <input type="submit" value="Register">
        <a href="login.php">Login</a>
    </form>
</body>
</html>
