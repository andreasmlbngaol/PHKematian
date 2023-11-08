<?php
require "../../functions.php";
$id = $_POST['id'];
$query = "DELETE FROM employee WHERE id = '$id'";
if(!mysqli_query($conn, $query)) {
    alert('Error');
}
?>