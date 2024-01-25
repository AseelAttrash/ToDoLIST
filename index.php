<?php
session_start();

if (!isset($_SESSION['user'])) {
    // The user is not logged in. Redirect them to the login page
    header('Location: login.php');
    exit;
}

// Replace this section with your actual database connection details
$host = 'localhost';
$db = '212185938_211620240';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle the form submission for adding a new list
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['users'])) {
        $title = $_POST['title'];
        $users = $_POST['users'];
        $currentUserId = $_SESSION["user_id"];
        if (!in_array($currentUserId, $users)) {
            $users[] = $currentUserId;
        }
        $users = json_encode($users);

        // Replace this section with your actual database query to insert the new list
        $stmt = $conn->prepare("INSERT INTO lists (title, users) VALUES (:title, :users)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':users', $users);
        $stmt->execute();

        // Get the ID of the newly inserted list
        $newListID = $conn->lastInsertId();

        echo $newListID;
        exit;
    }

    $user_id = $_SESSION['user_id'];
    // Fetch lists associated with the user_id
    // Fetch all lists
    $stmt = $conn->prepare("SELECT * FROM lists");
    $stmt->execute();
    $allLists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Filter lists associated with the user_id
    $lists = array_filter($allLists, function ($list) use ($user_id) {
        $users = json_decode($list['users'], true);
        return in_array($user_id, $users);
    });

    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Main Page</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body id="mainPageId">
    <nav>
        <ul>
            <li><a href="#">Tasks page</a></li>
            <li style="margin-left: 70%;"><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
    <div class="parent-container">
        <div id="main-page">
            <h1>All Lists of Tasks</h1>
            <p id="display-text"></p>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Creation Date</th>
                            <th>Users</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lists as $list) : ?>
                            <tr>
                                <td><?php echo $list['title']; ?></td>
                                <td><?php echo $list['creation_date']; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="userListDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            See list users
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="userListDropdown">
                                            <?php $users = json_decode($list['users'], true); ?>
                                            <?php foreach ($users as $user_id) : ?>
                                                <?php
                                                $stmt = $conn->prepare("SELECT * FROM users WHERE ID = :user_id");
                                                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                                $stmt->execute();
                                                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                                ?>
                                                <a class="dropdown-item" href="#"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </td>
                                <td onclick="location.href='list_page.php?list_id=<?php echo $list['id']; ?>';">
                                    See Details
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add the "Add New List" button -->
            <button id="addNewListButton" class="btn btn-primary">Add New List</button>
        </div>
    </div>


    <script>
        // JavaScript code for the main page
        $(document).ready(function() {
            // Function to open the add list modal
            function openAddListModal() {
                $('#addListModal').modal('show');
            }

            // Function to close the add list modal
            function closeAddListModal() {
                $('#addListModal').modal('hide');
            }

            // Handle the form submission for adding a new list
            $('#addListForm').submit(function(event) {
                event.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        // Close the add list modal
                        closeAddListModal();

                        // Reload the page to display the newly added list
                        window.location.href = `list_page.php?list_id=${data}`;
                    },
                    error: function() {
                        alert('Error occurred while adding the new list.');
                    }
                });
            });

            // Attach click event to "Add New List" button to open the modal
            $('#addNewListButton').click(function() {
                openAddListModal();
            });

            // Attach click event to "Cancel" button to close the modal
            $('#cancelAddListButton').click(function() {
                closeAddListModal();
            });
        });
    </script>

    <div class="modal" id="addListModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addListForm">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="users">Users:</label>
                            <select class="form-control" id="users" name="users[]" multiple required>
                                <?php foreach ($allUsers as $user) : ?>
                                    <option value="<?php echo $user['ID']; ?>"><?php echo $user['email']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>