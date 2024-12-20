<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/
session_start();
require('connect.php');

$query2 = "SELECT * FROM categories ORDER BY id";
$statement2 = $db->prepare($query2);
$statement2->execute();
$categories = $statement2->fetchAll();

if (isset($_GET['title'])){
    $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_input(INPUT_GET, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_GET, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = $_GET['description'];
    $category =filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $query = "INSERT INTO books (title, author, price, description, category) values (:title, :author, :price, :description, :category)";
    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':author', $author);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':category', $category);

    header("Location: book.php?id=admin");
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
    <script src="https://cdn.tiny.cloud/1/q987xmo40tga76u7h0gxfrpwx4gzyxjcx99mlj7mactva36i/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: 'textarea'
      });
    </script>
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

            <?php if(isset($_GET['title'])): ?>
                <?php if (strlen($_GET['title']) == 0): ?>
                    <h1>You must enter a title</h1>
                <?php elseif (strlen($_GET['author']) == 0): ?>
                    <h1>You must enter an author</h1>
                <?php elseif (strlen($_GET['price']) == 0): ?>
                    <h1>You must enter a price</h1>
                <?php elseif (strlen($_GET['description']) == 0): ?>
                    <h1>You must enter a description</h1>
                <?php else: ?>
                    <?php $statement->execute() ?>
                    <h1>Your book has been created</h1>
                <?php endif ?>
            <?php endif ?>


            <form action="createBook.php" method="get">
                <label for="title">Title: </label>
                <input type="text" id="title" name="title" maxlength="255" minlength="1" size="145">

                <label for="author">Author: </label>
                <input type="text" id="author" name="author" maxlength="255" minlength="1" size="145">

                <label for="price">Price: </label>
                <input type="text" id="price" name="price" maxlength="255" minlength="1" size="145">

                <label for="category">Category: </label>
                <select id="category" name="category">
                    <?php foreach ($categories as $category): ?>
                        <option value=" <?=$category['id']?> "> <?=$category['name']?> </option>
                    <?php endforeach ?>
                </select>

                <label for="description">Description: </label>
                <textarea id="description" name="description"  rows="20" cols="110" maxlength="1500" minlength="1"></textarea>

                <input type="submit" value="Submit">
            </form>
        <?php else: ?>
            <h1>You Must Log As An Admin In To Access This Page</h1>
        <?php endif ?>
        </div>
    </div> <!-- End div "wrapper" -->
</body>
</html>