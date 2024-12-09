<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/
session_start();
require('connect.php');
require 'scripts\ImageResize.php';
require 'scripts\ImageResizeException.php';

function file_upload_path($original_filename, $upload_subfolder_name = 'images') {
    $current_folder = dirname(__FILE__);
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    return join(DIRECTORY_SEPARATOR, $path_segments);
    }

function file_is_an_image($temporary_path, $new_path) {
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type        = mime_content_type($temporary_path);

    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

    return $file_extension_is_valid && $mime_type_is_valid;
    }


function resize_image($new_image_path) {
    $image = new \Gumlet\ImageResize($new_image_path);
    $image->resizeToWidth(200);
    $image->save($new_image_path);
}

$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);

if ($image_upload_detected) {
        $image_filename       = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];
        $new_image_path       = file_upload_path($image_filename);

    if (file_is_an_image($temporary_image_path, $new_image_path)) {
        move_uploaded_file($temporary_image_path, $new_image_path);
        resize_image($new_image_path);

        $bookId = filter_input(INPUT_GET, 'bookId', FILTER_SANITIZE_NUMBER_INT);

        $imageQuery = "UPDATE books SET image = :image_filename WHERE bookId = $bookId";
        $imageStatement = $db->prepare($imageQuery);
        $imageStatement->bindValue(':image_filename', $image_filename);
        $imageStatement->execute();

    }
}

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
    $image = $book['image'];
    

    if (isset($_POST['delete'])) {
        $delete_query = "DELETE FROM books WHERE bookId = {$bookId}";
        $delete_statement = $db->prepare($delete_query);
        $delete_statement->execute();
        
    }

    if (isset($_POST['deleteImage'])) {
        $delete_query = "UPDATE books SET image = null WHERE bookId = $bookId";
        $delete_statement = $db->prepare($delete_query);
        $delete_statement->execute();
        
        unlink('images/'.$image);

    }

    if(isset($_GET['submitted'])) {
        $update_title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $update_author = filter_input(INPUT_GET, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $update_price = filter_input(INPUT_GET, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $update_category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);
        $update_description = $_GET['description']; //turned off sanitization for WYSIWYG html formatting

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
    <link type="text/css" rel="stylesheet" href="css/main.css">
    <title>Editing Book!</title>
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
                <br>
                <input type="submit" value="Submit"> 
            </form>
            <form method="post" id="delete">
                <button type="submit" name="delete" id="delete">Delete This Book</button>
            </form>
            <div>
                <form id="imageupload" action="editBook.php?bookId=<?=$_GET['bookId']?>" method="post" enctype="multipart/form-data">
                    <label for="image">Upload An Image For This Book</label>
                    <input type="file" name="image" id="image">
                    <input type="submit" name="submit" value="Upload">
                </form>
            </div>
            <?php if ($image !== null): ?>
                <div>
                    <form method="post" id="deleteImage">
                    <button type="submit" name="deleteImage" id="deleteImage">Delete Image For This Book</button>
                </form>
                </div>
            <?php endif?>
        <?php elseif (isset($_POST['delete'])): ?>
            <h1>Book Successfully Deleted</h1>
        <?php else: ?>
            <h1>You Must Log As An Admin In To Access This Page</h1>
        <?php endif ?>
        </div>
    </div> <!-- End div "wrapper" -->
</body>
</html>