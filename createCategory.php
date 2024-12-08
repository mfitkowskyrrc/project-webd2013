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

if (isset($_POST['delete'])) {
        $delete_query = "DELETE FROM categories WHERE id = {$_POST['category']}";
        $delete_statement = $db->prepare($delete_query);
        $delete_statement->execute();
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Create Category</title>
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
            <li><a href="index.php" class='active'>Home</a></li>
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

            <?php if(isset($_GET['name'])): ?>
                <?php if (strlen($_GET['name']) == 0): ?>
                    <h1>You must enter a name</h1>
                <?php else: ?>
                    <?php 
                    $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $query = "INSERT INTO categories (name) values (:name)";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':name', $name);
                    $statement->execute() ?>
                    <h1>Your category has been created</h1>
                    <?php $_GET['name'] = '' ?>
                <?php endif ?>
            <?php endif ?>
            <form method="post">
                <label for="category">Current Categories: </label>
                <select id="category" name="category">
                    <?php foreach ($categories as $category): ?>
                        <option value=" <?=$category['id']?> "> <?=$category['name']?> </option>
                    <?php endforeach ?>
                </select>
                <button type="submit" name="delete" id="delete">Delete This Category</button>
            </form>
            
            <form action="createCategory.php" method="get">
                <label for="name">Add A Category: </label>
                <input type="text" id="name" name="name" maxlength="52" minlength="1" size="20">

                <input type="submit" value="Submit">
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