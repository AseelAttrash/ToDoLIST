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
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}



// Add a new task using Ajax
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addTask') {
    if (!empty($_POST['title'])) {
        $title = $_POST['title'];
        $list_id = $_POST['list_id'];
        $responsible_user = isset($_POST['responsible_user']) ? $_POST['responsible_user'] : $_SESSION['user'];

        $stmt = $conn->prepare("INSERT INTO tasks (list_id, title, responsible_user) VALUES (:list_id, :title, :responsible_user)");
        $stmt->bindParam(':list_id', $list_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':responsible_user', $responsible_user);
        $stmt->execute();

        echo 'success';
        exit;
    }
}

// Mark a task as completed using Ajax
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'completeTask') {
    if (isset($_POST['task_id']) && isset($_POST['completed'])) {
        $task_id = $_POST['task_id'];
        $completed = $_POST['completed'];

        $stmt = $conn->prepare("UPDATE tasks SET done = :completed WHERE id = :task_id");
        $stmt->bindParam(':completed', $completed, PDO::PARAM_BOOL);
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        echo 'success';
        exit;
    }
}

// Delete a task using Ajax
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'deleteTask') {
    if (isset($_POST['task_id'])) {
        $task_id = $_POST['task_id'];

        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :task_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        echo 'success';
        exit;
    }
}

$stmt = $conn->prepare("SELECT * FROM lists WHERE id = :list_id");
$stmt->bindParam(':list_id', $_GET['list_id']);
$stmt->execute();
$list = $stmt->fetch(PDO::FETCH_ASSOC);
$users = json_decode($list['users'], true);
$current_user_id = $_SESSION["user_id"];
if (!in_array($current_user_id, $users)) {
    header('Location: index.php');
    exit;
}


// Fetch tasks associated with the list_id
$stmt = $conn->prepare("SELECT * FROM tasks WHERE list_id = :list_id");
$stmt->bindParam(':list_id', $_GET['list_id']);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Sample List</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="smileyToggle.js"></script>
</head>

<body id="samplePageID">
    <nav>
        <ul>
            <li><a href="#"> <?php echo $list['title'] ?> Tasks page </a></li>
            <li><a href="index.php">Tasks page</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
    <br>
    <div class="container">
        <h1><?php echo $list['title'] ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Added</th>
                    <th>Responsible User</th>
                    <th>Completed</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task) : ?>
                    <tr>
                        <td <?php if ($task['done']) echo "class='strikethrough'"; ?>><?php echo $task['title']; ?></td>
                        <td <?php if ($task['done']) echo "class='strikethrough'"; ?>><?php echo $task['date_added']; ?></td>
                        <td <?php if ($task['done']) echo "class='strikethrough'"; ?>>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM users WHERE ID = :user_id");
                            $stmt->bindParam(':user_id', $task['responsible_user'], PDO::PARAM_INT);
                            $stmt->execute();
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                        </td>
                        <td>
                            <input type="checkbox" onchange="toggleSmiley(this)" <?php if ($task['done']) echo "checked"; ?> />
                            <img class="smiley" <?php if (!$task['done']) echo "src='IMG/sad.png'"; ?> <?php if ($task['done']) echo "src='IMG/smile.png'"; ?> alt="">
                        </td>
                        <td>
                            <form class="form-inline" method="post" onsubmit="return confirm('Are you sure you want to delete the task?');">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <input type="hidden" name="delete_task" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

        <br>
        <h2>Add Task</h2>
        <form id="addTaskForm" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="responsible_user">Responsible User:</label>
                <select class="form-control" id="responsible_user" name="responsible_user">
                    <?php $users = json_decode($list['users'], true); ?>
                    <?php foreach ($users as $user_id) : ?>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM users WHERE ID = :user_id");
                        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <option value="<?php echo $user['ID']; ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_task" class="btn btn-primary">Add Task</button>
        </form>
        <br>

        <button onclick="location.href='index.php';">Back to Main Page</button>
    </div>

    <script>
        // AJAX call to add a new task
        $("#addTaskForm").submit(function(e) {
            e.preventDefault();
            const title = $("#title").val();
            const responsible_user = $("#responsible_user").val();
            const list_id = <?php echo $list['id']; ?>;
            $.ajax({
                type: "POST",
                url: "list_page.php",
                data: {
                    title: title,
                    responsible_user: responsible_user,
                    list_id: list_id,
                    add_task: 1,
                    action: "addTask"
                },
                success: function(response) {
                    location.reload();
                }
            });
        });

        // AJAX call to update task completion status
        $("input[type='checkbox']").change(function() {
            const task_id = $(this).closest("tr").find("input[name='task_id']").val();
            const completed = $(this).prop("checked") ? 1 : 0;
            $.ajax({
                type: "POST",
                url: "list_page.php",
                data: {
                    task_id: task_id,
                    completed: completed,
                    action: "completeTask"
                },
                success: function(response) {}
            });
        });

        // AJAX call to delete a task
        $(".btn-danger").click(function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete the task?")) {
                const task_id = $(this).closest("tr").find("input[name='task_id']").val();
                $.ajax({
                    type: "POST",
                    url: "list_page.php",
                    data: {
                        task_id: task_id,
                        delete_task: 1,
                        action: "deleteTask"
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        });
    </script>

</body>

</html>