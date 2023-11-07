<?php
require "../functions.php";
session_start();
if(!isset($_SESSION['loginId'])) {
    jumpTo('../');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD MENU</title>
</head>
<body>
    <a href="outlet.php">Outlet List</a>
    <a href="survey.php">Survey Result</a>
    <a href="employee.php">Employee</a>
    <a href="selling.php">Selling</a>
    <a href="recipe.php">Recipe</a>
</body>
</html>