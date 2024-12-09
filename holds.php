<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/
session_start();
require('connect.php');

$query = "SELECT * FROM holds";
$statement = $db->prepare($query);
$statement->execute();
$holds = $statement->fetchAll();

if (isset($_POST['delete'])) {
        $holdId = filter_input(INPUT_POST,'holdId', FILTER_SANITIZE_NUMBER_INT);
        
        $delete_query = "DELETE FROM holds WHERE holdId = $holdId";
        $delete_statement = $db->prepare($delete_query);
        $delete_statement->execute();

        header('location: holds.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/main.css">
    <title>holds</title>
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
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == True): ?>
        <ul id="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="books.php">All Books</a></li>
            <li><a href="books.php?search=&searchtype=1">Paperbacks</a></li>
            <li><a href="books.php?search=&searchtype=2">Hardcovers</a></li>
            <li><a href="books.php?search=&searchtype=3">Audiobooks</a></li>
            <?php if ($_SESSION['loggedin'] == 0 ): ?>
                <li><a href="login.php">Log In</a></li>
            <?php elseif ($_SESSION['loggedin'] == 1 ): ?>
                <li><a href="login.php?logout=logout">Log Out</a></li>
            <?php endif ?>
            <?php if (($_SESSION['admin'] == 1)): ?>
                <li><a href="admin.php">Admin Dashboard</a></li>
            <?php endif ?>
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
                    <th>Hold ID</th>
                    <th>Book ID</th>
                    <th>Username</th>
                    <th>Date Placed</th>
                </tr>
                <?php foreach ($holds as $hold): ?>
                    <tr>
                        <td><?=$hold['holdId']?> </td>
                        <td><?=$hold['bookId']?> </a></td>
                        <td><?=$hold['username']?> </td>
                        <td><?=$hold['dateplaced']?> </td>
                    </tr>
                <?php endforeach ?>
            </table>
            <div id='users'>
                <form action="holds.php" method="post">
                    <h1>Delete a Hold</h1>
                    <label for="holdId">Hold ID: </label>
                    <input type="text" name="holdId" id="holdId">
                    <div id="submits">
                        <input type="submit" name="delete" id="delete" value="Delete"></input>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <h1>You Must Log As An Admin In To Access This Page</h1>
        <?php endif ?>
        </div>
    </div> <!-- End div "wrapper" -->
</body>
</html>