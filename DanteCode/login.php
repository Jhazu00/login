<?php
session_start();

$conn = new mysqli("localhost", "root", "", "login_register");

$error = ''; // To show error messages

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]); // do NOT hash here

    if (empty($email) || empty($password)) {
        $error = "Email or Password is missing!";
    } else {
        $stmt = $conn->prepare("SELECT id, last_name, first_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($users_id, $Last_name, $First_name, $users_email, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION["users_id"] = $users_id;
                $_SESSION["last_name"] = $Last_name;
                $_SESSION["first_name"] = $First_name;
                $_SESSION["users_email"] = $users_email;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Incorrect password. Try again.";
            }
        } else {
            $error = "No user found with that email.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>LOGIN PROG</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style1.css"> 
</head>
<body>
    <div class="login-container">
        
        <!-- Show error message -->
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>
