<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');

$query = "SELECT * FROM books ORDER BY title";
$statement = $db->prepare($query);
$statement->execute();
$books = $statement->fetchAll();

$query2 = "SELECT * FROM categories ORDER BY id";
$statement2 = $db->prepare($query2);
$statement2->execute();
$categories = $statement2->fetchAll();
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
            <?php endif ?>
        </ul>        
        <content>
            <div id="books">
            <h1>All Of Our Books, Sorted By Title</h1>
            <table>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Price</th>
                </tr>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><a href="book.php?id= <?=$book['bookId']?> "> <?=$book['title']?> </a></td>
                        <td><?=$book['author']?></td>
                        <td><?=$categories[$book['category']-1]['name']?></td>
                        <td><?=$book['price']?></td>
                    </tr>
                <?php endforeach ?>
            </table>
            
        </div>

          

        </content>
        
    </div> <!-- End div "wrapper" -->

</body>
</html>