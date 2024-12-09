<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if (isset($_POST['register'])) {
    $query = "INSERT INTO users (username, password) values (:username, :password)";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password1);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/main.css">
    <title>Users</title>
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


        

        
        <form action="register.php" method="POST">
            <input type="hidden" name="register" id="register">
            <label for="username">Desired Username: </label>
            <input type="text" id="username" name="username" maxlength="52" minlength="1" size="50">

            <label for="password">Enter Password: </label>
            <input type="password" id="password1" name="password1" maxlength="52" minlength="1" size="50">
            <label for="password">Confirm Password: </label>
            <input type="password" id="password2" name="password2" maxlength="52" minlength="1" size="50">

            <input type="submit" value="Submit">
        </form>
        <p>Have an account? Log In <a href="login.php">Here</a></p>

        <?php if(isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])): ?>
            <?php if (strlen($_POST['username']) == 0): ?>
                <h1>You must enter a username</h1>
            <?php elseif (strlen($_POST['password1']) == 0): ?>
                <h1>You must enter a password</h1>
            <?php elseif (strcmp($_POST['password1'], $_POST['password2']) == 1 ): ?>
                <h1>Your passwords must match</h1>
            <?php elseif (strlen($_POST['username']) > 0 && strcmp($_POST['password1'], $_POST['password2']) == 0 ): ?>
                <?php $statement->execute() ?>
                <?php header('location: register.php') ?>
            <?php endif ?>
        <?php endif ?>
    </div> <!-- End div "wrapper" -->
</body>
</html>