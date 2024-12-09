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
            </table>
        </div> 

            <?php if (($_COOKIE['admin'] == 1)): ?>
            <form method='get' action='editBook.php'>
                <input type="hidden" name="bookId" value=<?=$_GET['id']?>>
                <input type="submit" value="Edit This Book">
            </form> 
            <?php endif ?>        
        </div>
    </div> 
</body>
</html>