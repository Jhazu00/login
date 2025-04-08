<?php
session_start();

if (!isset($_SESSION["users_id"])) {
    header("Location: login.php");
    exit;
}


$file_path = "C:/xampp/htdocs/DanteCode/122.jpg";
$web_path = "http://localhost/DanteCode/122.jpg";

if (file_exists($file_path)) {
    echo "<img src='$web_path' alt='Profile Picture' width='150'>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Fixed height */
            margin: 0;
            font-family: "Times New Roman", Times, serif;
            background-color: #f0f0f0; /* Changed to a lighter color for better readability */
        }
        .dashboard-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: left;
            background: white;
            padding: 50px; /* Adjusted padding */
            border-radius: 15px; /* Reduced border radius */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 800px; /* Adjusted width */
        }

        .profile-picture {
            width: 150px; /* Adjusted size */
            height: 150px; /* Adjusted size */
            border-radius: 50%; /* Corrected to 50% for circular shape */
            object-fit: cover;
            border: 3px solid rgb(6, 66, 131);
            margin-left: 20px; /* Adds spacing between text and picture */
        }

        h1 {
            margin-bottom: 10px;
            color: #333;
        }
        p {
            color: #555;
            font-size: 18px;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background: #0056b3; /* Adjusted hover color */
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
    <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" width="200">
        <div>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["last_name"] . " " . $_SESSION["first_name"]); ?>!</h1>
        <p>Your email: <?php echo htmlspecialchars($_SESSION["users_email"]); ?></p>
        <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>