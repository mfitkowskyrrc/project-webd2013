<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');

$query = "SELECT * FROM books WHERE bookId = {$_GET['id']}";
$statement = $db->prepare($query);
$statement->execute();
$book = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Books-R-Us</title>
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
        <ul id="menu">
            <li><a href="index.php" class='active'>Home</a></li>
            <li><a href="books.php">Books</a></li>
            <?php if ($_COOKIE['loggedin'] == 0): ?>
                <li><a href="login.php">Log In</a></li>
            <?php elseif (($_COOKIE['admin'] == 1)): ?>
                <li><a href="admin.php">Admin Dashboard</a></li>
                <li><a href=editBook.php?bookId=<?=$book['bookId']?>>Edit This Book</a></li>
            <?php endif ?>
        </ul> 
        <div id="books">
            <table id="book">
                <tr>
                    <td>Title: </td>
                    <td><?=$book['title'] ?></td>
                </tr>
                <tr>
                    <td>Author: </td>
                    <td><?=$book['author'] ?></td>
                </tr>
                <tr>
                    <td>Price: </td>
                    <td>$<?=$book['price'] ?></td>
                </tr>
                <tr>
                    <td>Description: </td>
                    <td><?=$book['description'] ?></td>
                </tr>
            </div>          
        </div>
    </div> 
</body>
</html>