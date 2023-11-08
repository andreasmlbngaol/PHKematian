<?php
require "../../functions.php";
session_start();
if(!isset($_SESSION['loginId'])) {
    jumpTo("../../");
}

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $outletId = $_POST['outlet'];
    if(addEmployee($_POST)) {
        alert('Success');
    }
}
$districts = getOutletDistrict();
$positions = getPosition();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADD EMPLOYEE</title>
    <script src="../../src/js/jquery-3.5.1.min.js"></script>
</head>
<body>
    <h1>ADD EMPLOYEE</h1>
    <a href="../employee.php">Back</a>
    <form action="" method="post">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" autocomplete="off" required>
        </div>

        <div>
            <label for="position">Position</label>
            <select name="position" id="position" required>
                <option value="">-- Choose --</option>
                <?php foreach ($positions as $position) { ?>
                    <option value="<?php echo $position['code'] ?>"><?php echo $position['meaning'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label for="district">District</label>
            <select name="district" id="district" required>
                <option value="" selected>-- Choose --</option>
                <?php foreach ($districts as $district) { ?>
                    <option value="<?php echo $district['district'] ?>"><?php echo $district['district'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="area">Area</label>
            <select name="area" id="area" required>
                <option value="" selected>-- Choose --</option>
            </select>
        </div>

        <div>
            <label for="outlet">Outlet</label>
            <select name="outlet" id="outlet" required>
                <option value="" selected>-- Choose --</option>
            </select>
        </div>
        <button type="submit" name="submit" id="submit">Insert</button>
    </form>
    <script>
        $('#district').change(function() {
            var district = $(this).val();
            $.ajax({
                type: "POST",
                url: "area.php",
                data: {district: district},
                success: function(result) {
                    $('#area').html(result);
                }
            });

            $('#area').change(function() {
                var area = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "outlet.php",
                    data: {district: district, area: area},
                    success: function(result) {
                        $('#outlet').html(result);
                    }
                });
            });
        });

        $('#rating').change(function() {
            var rating = $(this).val();
            $('#rating-value').html(rating);
        });
    </script>
</body>
</html>