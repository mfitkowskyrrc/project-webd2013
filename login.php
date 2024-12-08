<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');

$username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$query = "SELECT * FROM users WHERE username ='$username'";
$statement = $db->prepare($query);

if ($_COOKIE['logout'] == 1 ) {
    setcookie('logout', 0);
    setcookie('loggedin', False);
    setcookie('admin', False);

    header('location: login.php');
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
    <title>Books-R-Us Login</title>
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


        <?php if(isset($_GET['username']) && isset($_GET['password'])): ?>
            <?php if (strlen($_GET['username']) == 0 && strlen($_GET['password']) == 0): ?>
                <h1>You must enter a username</h1>
            <?php else: ?>
                <?php $statement->execute() ?>
                <?php $users = $statement->fetch() ?>

                <?php if ( strcmp($users['username'], $username) == 0 ): ?>
                    <?php if ( strcmp($users['password'], $password) == 0 ): ?>
                        <?php setcookie('loggedin', True) ?>                        
                        <h1>You have been logged in</h1>
                        <?php if ($users['admin'] == 1): ?>
                            <h2>Admin User Verified.</h2>
                            <h2>Go to <a href="admin.php">Admin Dashboard</a></h2>
                            <?php setcookie('admin', True) ?>
                        <?php endif ?>
                    <?php elseif ( strcmp($users['password'], $password) == 1 ): ?>
                        <h1>Incorrect Login Details, Try Again</h1>
                    <?php endif ?>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>


        <form action="login.php" method="get">
            <label for="username">Username: </label>
            <input type="text" id="username" name="username" maxlength="52" minlength="1" size="50">

            <label for="password">Password: </label>
            <input type="text" id="password" name="password" maxlength="52" minlength="1" size="50">

            <input type="submit" value="Submit">
        </form>
        <p>Don't have an account? Register <a href="register.php">Here</a></p>
    </div> <!-- End div "wrapper" -->
</body>
</html>