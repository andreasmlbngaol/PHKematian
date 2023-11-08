<?php
require "../../functions.php";
$district = $_POST['district'];
$area = $_POST['area'];
$outlets = getOutlet($district, $area);
?>


<option value="all" selected>--Choose--</option>
<?php foreach($outlets as $outlet) { ?>
<option value="<?php echo $outlet['outlet'] ?>"><?php echo $outlet['outlet'] ?></option>
<?php } ?>