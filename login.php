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
            <div id='adminlogin'>
                <a href="admin.php">Admin</a>
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
            
        </div>
    </div> <!-- End div "wrapper" -->
</body>
</html>