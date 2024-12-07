<?php

/*******w******** 
    
    Name: Michael Fitkowsky
    Date: Dec 6, 2024
    Description: Project

****************/

require('connect.php');

$twenty_minutes_from_now = time() + 1200;

if (!isset($_COOKIE['loggedin'])) {
        setcookie('admin', False, $twenty_minutes_from_now);
        setcookie('loggedin', False, $twenty_minutes_from_now);
    } 

//obtain posts from database "posts"
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
            <li><a href="books.php">Books</a></li>
            <?php if ($_COOKIE['loggedin'] == 0): ?>
                <li><a href="login.php">Log In</a></li>
            <?php elseif (($_COOKIE['admin'] == 1)): ?>
                <li><a href="admin.php">Admin Dashboard</a></li>
            <?php endif ?>
        </ul> 
        <content>
            <div id="description">
                <p><b>Nestled in the heart of Winnipeg, Books-R-Us is a cozy, family-owned bookstore that celebrates the magic of reading.</b></p>
                <p>We offer a carefully curated selection of new and pre-loved books across all genres, perfect for readers of all ages.</p>
                Whether you're hunting for a bestseller, a timeless classic, or a hidden gem, you'll find it here.</p>
                <p>Stop by for personalized recommendations, a warm atmosphere, and a shared love for stories.</p>
            </div>
            <div id="storeinfo">
                <div id="hours">
                    <table>
                        <tr>
                            <th>Day of Week</th>
                            <th>Hours</th>
                        </tr>
                        <tr>
                            <td>Sunday </td>
                            <td>15:00 - 18:00 </td>
                        </tr>
                        <tr>
                            <td>Monday </td>
                            <td>Closed </td>
                        </tr>
                        <tr>
                            <td>Tuesday </td>
                            <td>09:00 - 14:00 </td>
                        </tr>
                        <tr>
                            <td>Wednesday </td>
                            <td>09:00 - 14:00 </td>
                        </tr>
                        <tr>
                            <td>Thursday </td>
                            <td>09:00 - 14:00 </td>
                        </tr>
                        <tr>
                            <td>Friday </td>
                            <td>12:00 - 17:00 </td>
                        </tr>
                        <tr>
                            <td>Saturday </td>
                            <td>05:00 - 13:00 </td>
                        </tr>
                        
                    </table>
                </div>
                <div id="location">
                    <h1>Located in the underground shopping center at Portage & Main</h1>
                    <h1>*Google Maps API insert here*</h1>
                </div>
            </div>

        </content>
        
    </div> <!-- End div "wrapper" -->

</body>
</html>