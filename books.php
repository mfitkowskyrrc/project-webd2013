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

$query2 = "SELECT * FROM categories ORDER BY id";
$statement2 = $db->prepare($query2);
$statement2->execute();
$categories = $statement2->fetchAll();

if (isset($_GET['search'])) {
    $search = strtolower(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $searchtype = $_GET['searchtype'];

    if ($searchtype > 0) {
        $searchQuery = "SELECT * FROM books WHERE title LIKE CONCAT('%',:search,'%') OR author LIKE CONCAT('%',:search,'%') AND category = :searchtype";
        $searchStatement = $db->prepare($searchQuery);
        $searchStatement->bindValue(':search', $search);
        $searchStatement->bindValue(':searchtype', $searchtype);
    } else {
        $searchQuery = "SELECT * FROM books WHERE title LIKE CONCAT('%',:search,'%') OR author LIKE CONCAT('%',:search,'%')";
        $searchStatement = $db->prepare($searchQuery);
        $searchStatement->bindValue(':search', $search);
    }
    
    $searchStatement->execute();
    $results = $searchStatement->fetchAll();
    $books = $results;
}

if (isset($_GET['sort']) && !isset(($GET['search']))){
    $sortType = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $sortQuery = "SELECT * FROM books ORDER BY $sortType";
    $sortStatement = $db->prepare($sortQuery);
    $sortStatement->execute();

    $sorted = $sortStatement->fetchAll();
    $books = $sorted;
}
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
            <li><a href="books.php">All Books</a></li>
            <li><a href="books.php?search=&searchtype=1">Paperbacks</a></li>
            <li><a href="books.php?search=&searchtype=2">Hardcovers</a></li>
            <li><a href="books.php?search=&searchtype=3">Audiobooks</a></li>
            <?php if ($_COOKIE['loggedin'] == 0 ): ?>
                <li><a href="login.php">Log In</a></li>
            <?php elseif (($_COOKIE['admin'] == 1)): ?>
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
        <content>
            <div id="books">
                <h1>Our Entire Selection of Books
                    <?php if (isset($_GET['sort'])) {echo "(sorted by ".$sortType.")";} ?>
                </h1>
                <div id="searchbox">
                    <form method="get">
                        <label for="search">Search For Book: </label>
                        <input type="text" id="search" name="search" maxlength="255" minlength="1" size="30" value="<?php if(isset($_GET['search'])) {echo $_GET['search'];} ?>">
                        <label for="searchtype">Search In: </label>
                        <select id="searchtype" name="searchtype">
                            <option value=0>All</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?=$cat['id']?>"> <?=$cat['name']?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <input type="submit" value="Search">
                    </form>
                    <div>
                        <?php if ($_COOKIE['loggedin'] == 1): ?>
                            <form method="get">
                                <label for="sort">Sort List By: </label>
                                <select id="sort" name="sort">
                                    <option value="title">Title</option>
                                    <option value="author">Author</option>
                                    <option value="price">Price</option>
                                </select>
                                <input type="submit" value="Sort">
                            </form>
                        <?php endif ?>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                    </tr>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><a href="book.php?id=<?=$book['bookId']?> "> <?=$book['title']?> </a></td>
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