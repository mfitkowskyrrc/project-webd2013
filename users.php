<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');

$query = "SELECT * FROM users ORDER BY userId";
$statement = $db->prepare($query);
$statement->execute();
$users = $statement->fetchAll();

$userId = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$admin = filter_input(INPUT_POST, 'admin', FILTER_SANITIZE_NUMBER_INT);


if (isset($_POST['delete'])) {
        $delete_query = "DELETE FROM users WHERE username = :username";
        $delete_statement = $db->prepare($delete_query);
        $delete_statement->bindValue(':username', $username);
        $delete_statement->execute();

        header('location: users.php');
        exit();
    }

if (isset($_POST['add'])) {
    $query = "INSERT INTO users (username, password, admin) values (:username, :password, :admin)";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':admin', $admin);
    $statement->execute();

    header('location: users.php');
    exit();
}
if (isset($_POST['update'])) {
    $query = "UPDATE users SET password = :password, admin = :admin WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':admin', $admin);
    $statement->execute();

    header('location: users.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Users</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div id="wrapper">
        <header>
            <div id='headercontent'> 
                <h1>Welcome To Books-R-Us!</h1>
                <h2>Your Source For Premium, Pre-Loved Books</h2>
            </div>
        </header>
        <?php if (isset($_COOKIE['admin']) && $_COOKIE['admin'] == True): ?>
            <ul id="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="books.php">Books</a></li>
                <li><a href="admin.php">Admin Dashboard</a></li>

                <div id="searchboxtop">
                    <form action="books.php" method="get">
                        <label for="search">Search For Book: </label>
                        <input type="text" id="search" name="search" maxlength="255" minlength="1" size="15" value="<?php if(isset($_GET['search'])) {echo $_GET['search'];} ?>">
                        <input type="hidden" name="searchtype" value="0">
                        <input type="submit"  value="Search">
                    </form>
                </div>
            </ul>

            <table>
                <tr>
                    <th>User Id</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Admin (1 = Yes, 0 = No)</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?=$user['userId']?></a></td>
                        <td><?=$user['username']?></td>
                        <td><?=$user['password']?></td>
                        <td><?=$user['admin']?></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <div id='users'>
                <form action="users.php" method="post">
                    <h1>Add or Edit a User</h1>
                    <label for="username">Username: </label>
                    <input type="text" name="username" id="username">
                    <label for="password">Password: </label>
                    <input type="text" name="password" id="password">
                    <label for="admin">Admin?</label>
                    <select name="admin" id="admin">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    <div id="submits">
                        <input type="submit" name="add" id="add" value="Add"></input>
                        <input type="submit" name="update" id="update" value="Update"></input>
                        <input type="submit" name="delete" id="delete" value="Delete"></input>
                    </div>
                </form>
            </div>
            
            
                
        <?php else: ?>
            <ul id="menu">
                <li><a href="index.php" class='active'>Home</a></li>
                <li><a href="books.php">Books</a></li>
                <li><a href="login.php">Log In</a></li>
            </ul>
            <h1>You Must Log As An Admin In To Access This Page</h1>
        <?php endif ?>
        </div>
    </div> <!-- End div "wrapper" -->
</body>
</html>