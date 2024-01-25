<?php
// Replace this section with your actual database connection details
$host = 'localhost';
$db = '212185938_211620240';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Replace this section with your actual database query to fetch lists
    $stmt = $conn->prepare("SELECT * FROM lists");
    $stmt->execute();
    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate the HTML for the lists table
    $html = '<table>';
    $html .= '<tr><th>Title</th><th>Creation Date</th><th>Users</th></tr>';
    foreach ($lists as $list) {
        $html .= '<tr>';
        $html .= '<td><a href="list_page.php?list_id=' . $list['id'] . '">' . $list['title'] . '</a></td>';
        $html .= '<td>' . $list['creation_date'] . '</td>';
        $html .= '<td>' . $list['users'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    echo $html;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
