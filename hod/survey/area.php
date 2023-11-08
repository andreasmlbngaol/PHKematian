<?php
require "../../functions.php";
$district = $_POST['district'];
$areas = getOutletArea($district);
?>

<option value="all" selected>-- Choose --</option>
<?php foreach ($areas as $area) { ?>
    <option value="<?php echo $area['area'] ?>"><?php echo $area['area'] ?></option>
<?php } ?>