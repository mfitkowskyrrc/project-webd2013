<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/
session_start();
require('connect.php');

$query = "SELECT * FROM books WHERE bookId = {$_GET['id']}";
$statement = $db->prepare($query);
$statement->execute();
$book = $statement->fetch();

$query = "SELECT * FROM users ORDER BY userId";
$statement = $db->prepare($query);
$statement->execute();
$users = $statement->fetchAll();

$query = "SELECT * FROM comments WHERE bookId = {$_GET['id']} ORDER BY created";
$statement = $db->prepare($query);
$statement->execute();
$comments = $statement->fetchAll();

if (isset($_POST['addComment'])) {
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO comments (bookId, username, comment) VALUES (:bookId, :username, :comment)";
    $statement = $db->prepare($query);
    $statement->bindValue(":bookId", $_POST['bookId']);
    $statement->bindValue(":username", $_POST['username']);
    $statement->bindValue(":comment", $comment);
    $statement->execute();

    $_POST['addComment'] = null;
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/main.css">
    <title>Books-R-Us</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <div id='headercontent'> 
                <h1>Welcome To Books-R-Us!</h1>
                <h2>Your Source For Premium, Pre-Loved Books</h2>
            </div>
        </header>
         <ul id="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="books.php">All Books</a></li>
            <li><a href="books.php?search=&searchtype=1">Paperbacks</a></li>
            <li><a href="books.php?search=&searchtype=2">Hardcovers</a></li>
            <li><a href="books.php?search=&searchtype=3">Audiobooks</a></li>
            <?php if ($_COOKIE['loggedin'] == 0 ): ?>
                <li><a href="login.php">Log In</a></li>
            <?php elseif ($_COOKIE['loggedin'] == 1 ): ?>
                <li><a href="login.php?logout=1">Log Out</a></li>
            <?php endif ?>
            <?php if (($_COOKIE['admin'] == 1)): ?>
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
 
        <div id="books">
            <?php if ($book['image'] !== null): ?>
                <img src="images/<?=$book['image']?>">
            <?php endif ?>
            <table id="book">
                <tr>
                    <td><b>Title: </b></td>
                    <td><?=$book['title'] ?></td>
                </tr>
                <tr>
                    <td><b>Author: </b></td>
                    <td><?=$book['author'] ?></td>
                </tr>
                <tr>
                    <td><b>Price: </b></td>
                    <td>$<?=$book['price'] ?></td>
                </tr>
                <tr>
                    <td><b>Description: </b></td>
                    <td><?=$book['description'] ?></td>
                </tr>  
            <?php if (($_COOKIE['admin'] == 1)): ?>
            <tr>
                <td>
                    <form method='get' action='editBook.php?id=<?=$book['bookId']?>'>
                        <input type="hidden" name="bookId" value=<?=$book['bookId']?>>
                        <input type="submit" value="Edit This Book">
                    </form> 
                </td>
                <td></td>
            </tr>
            <?php endif ?> 
            </table> 
        </div> 
        

        <table id="comments">
            <h1>Comments</h1>
            <tr>
                <th>User</th>
                <th>Comment</th>
            </tr>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?=$comment['username']?></td>
                <td><?=$comment['comment']?></td>
            </tr>
        <?php endforeach ?>
        </table>
        <?php if (($_COOKIE['loggedin'] == 1)): ?>
            <h2>Leave a Comment</h2>
            <div id="enterComment">
                <form method='POST' action='Book.php?id=<?=$book['bookId']?>'>
                    <input type="hidden" name="bookId" value=<?=$book['bookId']?>>
                    <input type="hidden" name="addComment" value='1'>
                    <input type="hidden" name="username" value='<?=$_SESSION['username']?>'>
                    <br>
                    <textarea name="comment" id="comment" maxlength=500 minlength=1 rows=4 cols=75 placeholder=""></textarea>
                    <br>
                    <input type="submit" value="Add a Comment">
                </form> 
            </div>
        <?php endif ?>      
    </div>
     
</body>
</html>