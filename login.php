<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "cbc_ims");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password (use password_verify if passwords are hashed)
        if ($user['password'] === $password) { // Change this to password_verify($password, $user['password']) if using hashing
            // Store user information in session
            $_SESSION['username'] = $user['username'];

             // Debug output before redirect
             echo "Redirecting to: main_admin/dashboard.php"; 

            // Redirect to main admin dashboard
            if ($user['username'] == 'main_admin') {
                header("Location: main_admin/dashboard.php"); // Adjust path as needed
                exit;
            } else {
                $error = "Access Denied. Only Main Admin can access this dashboard.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
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
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

  <div class="login-form">
    <div class="container">
      <div class="main">
        <div class="content">
          <h2>Login</h2>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="text" name="username" placeholder="Username" required/>
            <input type="password" name="password" placeholder="Password" required/>
            <a href="#">Forget password?</a>
            <button type="submit">Sign in</button>
            <p style="color: red;"><?php echo $error; ?></p>
          </form>
        </div>
        <div class="login-logo">
          <img src="cbc-logo.png" alt="CBC Logo">
        </div>
      </div>
    </div>
  </div>

</body>
</html>
