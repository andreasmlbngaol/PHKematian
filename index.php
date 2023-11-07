<?php
require "functions.php";
if(isset($_POST['submit'])) {
    if(login($_POST)) {
        alert('LOGIN');
        if($_SESSION['type'] == 'outlet') {
            jumpTo('outlet/');
        } else {
            jumpTo('hod/');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
</head>
<body>
    <form action="" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="username" autocomplete="off" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="password" required>
        </div>
        <button type="submit" id="submit" name="submit">Login</button>
    </form>
</body>
</html>