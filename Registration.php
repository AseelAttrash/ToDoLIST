<?php
$host = 'localhost';
$db = '212185938_211620240';
$user = 'root';
$password = '';
$error = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST['firstName'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            $error = "Email already exists.";
        } else {
            $query = "INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :surname, :email, :password)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            header('Location: login.php');
            exit;
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
    <title>Registration Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        .error {
            border: 1px solid red;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#email').blur(function() {
                const email = $(this).val();
                $.ajax({
                    url: 'check_email.php',
                    method: 'POST',
                    data: {
                        email: email
                    },
                    success: function(data) {
                        if (data !== '0') {
                            $('#email').addClass('error');
                            $('#signupbutton').attr('disabled',true);
                            $('#email-error').text('Email already exists in the system.');
                        } else {
                            $('#email').removeClass('error');
                            $('#email-error').text('');
                            // check email format
                            const validateEmail = (email) => {
                            return email.match(
                                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                            );
                            };

                            const validate = () => {
                            const $result = $('#result');
                            const email = $('#email').val();
                            $result.text('');

                            if(validateEmail(email)){
                                $result.text('Email is valid.');
                                $result.css('color', 'green');
                                $('#signupbutton').attr('disabled',false);
                            } else{
                                $result.text('Email is invalid.');
                                $result.css('color', 'red');
                                $('#signupbutton').attr('disabled',true);
                            }
                            return false;
                            }
                            validate();
                        }
                    },
                    error: function() {
                        alert('Error occurred during email validation.');
                    }
                });
            });
        });
    </script>

</head>

<body id="regBody">
    <nav>
        <ul>
            <li><a href="#">Registration Page</a></li>
        </ul>
    </nav>
    <div class="registration-container">
        <h2>Register</h2>
        <p><?php echo $error; ?></p>
        <form id="registrationForm" action="Registration.php" method="post">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span id="email-error" style="color: red;"></span>
            <p id="result"></p>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword"  onkeyup='check();' required>
            <p id='message'></p>
            <input id="signupbutton" type="submit" value="signUP" onclick="return checkPasswordConfirmation();">
        </form>
        <a href="login.php" class="back-to-login">Back to login</a>
    </div>

    <script>
        //check password 
        var check = function() {
            if (document.getElementById('password').value ==
                document.getElementById('confirmPassword').value) {
                document.getElementById('message').style.color = 'green';
                document.getElementById('message').innerHTML = 'Password confirmation matches.';
                $('#signupbutton').attr('disabled',false);
            } else {
                document.getElementById('message').style.color = 'red';
                document.getElementById('message').innerHTML = 'Password confirmation does not match.';
                $('#signupbutton').attr('disabled',true);
            }
        }

        

    </script>
</body>

</html>