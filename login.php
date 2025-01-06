<?php
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

        // Directly compare the plain-text password
        if ($password === $user['password']) {
            // Store user information in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ($user['role']) {
                case 'main_admin':
                    header("Location: admin/main_admin/dashboard.php");
                    break;
                case 'membership_admin':
                    header("Location: admin/membership_admin/dashboard.php");
                    break;
                case 'finance_admin':
                    header("Location: admin/finance_admin/dashboard.php");
                    break;
                case 'assimilation_admin':
                    header("Location: admin/assimilation_admin/dashboard.php");
                    break;
                default:
                    $error = "Invalid role.";
            }
            exit;
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
          <img src="assets/cbc-logo.png" alt="CBC Logo">
        </div>
      </div>
    </div>
  </div>

</body>
</html>
