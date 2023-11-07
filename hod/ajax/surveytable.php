<?php
require "../../functions.php";
if(isset($_POST['area'])) {
    $area = $_POST['area'];
} else {
    $area = 'all';
}
$district = $_POST['district'];
$results = getSurvey($district, $area);
?>


<tr>
    <th>No</th>
    <th>Date</th>
    <th>Outlet</th>
    <th>Area</th>
    <th>District</th>
    <th>Rating</th>
    <th>Description</th>
</tr>
<?php $i = 1; foreach($results as $result) { ?>
<tr>
    <td><?php echo $i ?></td>
    <td><?php echo $result['date'] ?></td>
    <td><?php echo $result['outlet'] ?></td>
    <td><?php echo $result['area'] ?></td>
    <td><?php echo $result['district'] ?></td>
    <td><?php echo $result['rating'] ?></td>
    <td><?php echo $result['description'] ?></td>
</tr>
<?php $i; } ?>