<?php
$host = 'localhost';
$db = '212185938_211620240';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        echo $count;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
