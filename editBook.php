<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');
$query2 = "SELECT * FROM categories ORDER BY id";
$statement2 = $db->prepare($query2);
$statement2->execute();
$categories = $statement2->fetchAll();

//check id
if (is_int(filter_input(INPUT_GET,'bookId', FILTER_VALIDATE_INT))){
    $bookId = filter_input(INPUT_GET, 'bookId', FILTER_SANITIZE_NUMBER_INT);
    $error = false;
    $error_message = "";

    $query = "SELECT * FROM books WHERE bookId = {$bookId}";
    $statement = $db->prepare($query);
    $statement->execute();
    $book = $statement->fetch();

    $title = $book['title'];
    $author = $book['author'];
    $price = $book['price'];
    $category = $book['category'];
    $description = $book['description'];
    

    if (isset($_POST['delete'])) {
        $delete_query = "DELETE FROM books WHERE bookId = {$bookId}";
        $delete_statement = $db->prepare($delete_query);
        $delete_statement->execute();
        
    }

    if(isset($_GET['submitted'])) {
        $update_title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $update_author = filter_input(INPUT_GET, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $update_price = filter_input(INPUT_GET, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $update_category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);
        $update_description = filter_input(INPUT_GET, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //check if title or content are too < 1 char
        if(strlen($update_title) < 1) {
            $error_message = "You Must Enter A Title";
            $error = true;
        } elseif (strlen($update_author) < 1) {
            $error_message = "You Must Enter An Author";
            $error = true;
        } elseif (strlen($update_price) < 1) {
            $error_message = "You Must Enter A Price";
            $error = true;
        }elseif ($error == false) {

            $update_query = "UPDATE books SET title = :title, author = :author, price = :price, category = :category, description = :description WHERE bookId = {$bookId}";
            $update_statement = $db->prepare($update_query);
            $update_statement->bindValue(':title', $update_title);
            $update_statement->bindValue(':author', $update_author);
            $update_statement->bindValue(':price', $update_price);
            $update_statement->bindValue(':category', $update_category);
            $update_statement->bindValue(':description', $update_description);
            $update_statement->execute();

            $title = $update_title;
            $author = $update_author;
            $price = $update_price;
            $category = $update_category;
            $description = $update_description;
        }
    }
} else {
    header("location: books.php");
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
    <title>Editing Book!</title>
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
            <?php endif ?>
        </ul> 
        <!--check to see if user requested deletion-->
        <?php if(!isset($_POST['delete'])): ?>

            <!-- check if form was modified and submitted-->
            <?php if (isset($_GET['submitted'])): ?>
                <?php if ($error == true): ?>
                    <h1><?= $error_message ?></h1>
                <?php else: ?>
                    <h1>Your changes have been submitted</h1>
                <?php endif ?>
            <?php endif?>

            <!--get input-->
            <form action="editBook.php" method="get">
                <input type="hidden" id="bookId" name="bookId" value="<?=$bookId?>">
                <input type="hidden" id="submitted" name="submitted" value="true">

                <label for="title">Title: </label>
                <input type="text" id="title" name="title" maxlength="255" minlength="1" size="145" value="<?=$title?>">

                <label for="author">Author: </label>
                <input type="text" id="author" name="author" maxlength="255" minlength="1" size="145"value="<?=$author?>">

                <label for="price">Price: </label>
                <input type="text" id="price" name="price" maxlength="255" minlength="1" size="145" value="<?=$price?>">

                <label for="category">Category: </label>
                <select id="category" name="category">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?=$cat['id']?>" 
                        <?php if ($cat['id'] == $category){echo "selected";} ?>> <?=$cat['name']?>
                        </option>
                    <?php endforeach ?>
                </select>

                <label for="description">Description: </label>
                <textarea id="description" name="description"  rows="20" cols="110" maxlength="1500" minlength="1"><?=$description?></textarea>
                <input type="submit" value="Submit">
            </form>
            <form method="post">
                <button type="submit" name="delete" id="delete">Delete This Book</button>
            </form>
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