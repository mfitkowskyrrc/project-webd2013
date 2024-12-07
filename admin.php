<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');

$query = "SELECT * FROM books";
$statement = $db->prepare($query);
$statement->execute();
$books = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Books-R-Us Admin</title>
</head>
<body>
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
                <li><a href="createBook.php">Add Book</a></li>
                <li><a href="createCategory.php">Add Category</a></li>
            </ul>
            <body>
                <div id="description">
                    <h1>Welcome to the Admin Dashboard</h1>
                </div>
                <ul>
                    <li><a href="createBook.php" >Add Book</a></li>
                    <li><a href="createCategory.php" >Add Category</a></li>
                </ul>
            </body>
        <?php else: ?>
            <ul id="menu">
                <li><a href="index.php" class='active'>Home</a></li>
                <li><a href="books.php">Books</a></li>
                <li><a href="login.php">Log In</a></li>
            </ul>
            <h1>You Must Log As An Admin In To Access This Page</h1>
        <?php endif ?>
    </div> 
</body>
</html>