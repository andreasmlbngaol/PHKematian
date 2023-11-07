<?php
require "../../functions.php";
if(isset($_POST['area'])) {
    $area = $_POST['area'];
} else {
    $area = 'all';
}
$district = $_POST['district'];
$outlets = getOutlet($district, $area);
?>

<tr>
    <th>ID</th>
    <th>Outlet</th>
    <th>Area</th>
    <th>District</th>
</tr>
<?php foreach($outlets as $outlet) { ?>
<tr>
    <td><?php echo $outlet['id'] ?></td>
    <td><?php echo $outlet['outlet'] ?></td>
    <td><?php echo $outlet['area'] ?></td>
    <td><?php echo $outlet['district'] ?></td>
</tr>
<?php } ?>