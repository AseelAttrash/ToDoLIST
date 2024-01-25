<?php
    $host = 'localhost';
    $db = '212185938_211620240';
    $user = 'root';
    $password = '';
    $error = '';
    $success = '';
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
        // Check if the email is unique
            $email_check_sql = "SELECT id FROM users WHERE email = :email";
            $email_check_stmt = $conn->prepare($email_check_sql);
            $email_check_stmt->bindParam(':email', $email);
            $email_check_stmt->execute();

            if ($email_check_stmt->rowCount() > 0) {
                $length = 32; // Length of the random hash in bytes
                $randomBytes = random_bytes($length);
                $hash = bin2hex($randomBytes);
                $query = "UPDATE users SET token=:hash where email=:email";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':hash', $hash);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $success= "Password recovery link has been sent to your email.";
            } else {
                
                $error= "Email does not exist.";
                
            }
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }

    $conn = null;
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body id="regBody">
    <nav>
        <ul>
          <li><a href="#">Forgot Password</a></li>
        </ul>
    </nav>
    <div class="registration-container">
        <h2>Forgot Password</h2>
        <p style="color: red;"><?php echo $error; ?></p>
        <p style="color: green;"><?php echo $success; ?></p>
        <form id="registrationForm" action="forgot-password.php" method="post">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <input id="signupbutton" type="submit"  value="Submit">
        </form>
        <a href="login.php" class="back-to-login">Back to login</a>
    </div>
</body>
</html>
