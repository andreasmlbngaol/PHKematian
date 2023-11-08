<?php
require "../../functions.php";
if(isset($_POST['area'])) {
    $area = $_POST['area'];
} else {
    $area = 'all';
}
$district = $_POST['district'];
$results = getEmployee($district, $area);
?>


<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Position</th>
    <th>Outlet</th>
    <th>Area</th>
    <th colspan="2">District</th>
</tr>
<?php $i = 1; foreach($results as $result) { ?>
<tr>
    <td><?php echo $result['id'] ?></td>
    <td><?php echo $result['name'] ?></td>
    <td><?php echo $result['position'] ?></td>
    <td><?php echo $result['outlet'] ?></td>
    <td><?php echo $result['area'] ?></td>
    <td><?php echo $result['district'] ?></td>
    <td><button class="fire-button" value="<?php echo $result['id'] ?>">Fire</button></td>
</tr>
<?php $i; } ?>