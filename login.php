<?php
session_start();



$host = 'localhost';
$db = '212185938_211620240';
$user = 'root';
$password = '';
$error = '';

try {
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stayConnected = isset($_POST['stayConnected']) ? true : false;

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Invalid email format";
    } else {
      $query = "SELECT * FROM users WHERE email = :email AND password = :password";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $password);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        // Add a session to the user to verify that he is logged in
        $_SESSION['user'] = $email;
        $_SESSION['user_id'] = $stmt->fetch(PDO::FETCH_ASSOC)['ID'];

        // If the user checked the checkbox that allows the user to stay logged in, 
        // a cookie must be created which will connect the user automatically and 
        // it will be kept by the user for 30 days.
        if ($stayConnected) {
          setcookie('user', $email, time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        // Redirect to mainPage.html and stop the script
        header('Location: index.php');
        exit();
      } else {
        $error = "Invalid email or password.";
      }
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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LogIn page</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body id="logPAGE" style="background-color: white;">
  <nav>
    <ul>
      <li><a href="#">welcome</a></li>
      <li><a href="Registration.php">Register</a></li>
    </ul>
  </nav>
  <br>
  <br>
  <div class="parent-container">
    <div class="form">
      <h3>Welcome to ToDo List Website!</h3>
      <div class="avater">
        <img class="avt" src="IMG/3.png.jpeg">
        <img class="avt" src="IMG/1.png.jpeg">
        <img class="avt" src="IMG/2.png.jpeg">
        <img class="avt" src="IMG/3.png.jpeg">
        <img class="avt" src="IMG/4.png.jpeg">
        <img class="avt" src="IMG/5.png.jpeg">
        <img class="avt" src="IMG/off.png.jpeg">
      </div>
      <div class="field">
        <form action="login.php" method="post">
          <h6>To login in please fill your email and password:</h6>
          <label>Email:</label>
          <br>
          <input required type="text" id="email" name="email" placeholder="username or mail" onkeyup="lami(this.value)" onmouseenter="lami(this.value);" onmouseleave="lami('r'); ">
          <br>
          <div class="error-container">
            <span id="emailError" class="error-message" hidden>Email is not valid</span>
          </div>
          <label>Password:</label>
          <br>
          <input required id="password" type="password" name="password" placeholder="password" onmouseenter="lami('p'); " onmouseleave="lami('r'); ">
          <br>
          <input type="checkbox" id="stayConnected" name="stayConnected">
          <label for="stayConnected">Stay connected</label>
          <p>forgot your password? <a href ="forgot-password.php"> Password Recovery</a> </p>
          <br>
          <input id="loginbutton" type="submit" value="Login">
        </form>
        <p><?php echo $error; ?></p>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="script.js"></script>
</body>

</html>