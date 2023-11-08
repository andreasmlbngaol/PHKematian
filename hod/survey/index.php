<?php
require "../../functions.php";
session_start();
if(!isset($_SESSION['loginId'])) {
    jumpTo("../../");
}

if(isset($_POST['submit'])) {
    if(insertSurvey($_POST)) {
        alert('Success');
    }
}
$districts = getOutletDistrict();
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
    <form action="" method="post">
        <div>
            <label for="district">District</label>
            <select name="district" id="district">
                <option value="all" selected>-- Choose --</option>
                <?php foreach ($districts as $district) { ?>
                    <option value="<?php echo $district['district'] ?>"><?php echo $district['district'] ?></option>
                <?php } ?> 
            </select>
        </div>

        <div>
            <label for="area">Area</label>
            <select name="area" id="area" required>
                <option value="all" selected>-- Choose --</option>
            </select>
        </div>

        <div>
            <label for="outlet">Outlet</label>
            <select name="outlet" id="outlet" required>
                <option value="all" selected>-- Choose --</option>
            </select>
        </div>

        <div>
            <label for="date">Date</label>
            <input type="date" name="date" id="date" required>
        </div>

        <div>
            <label for="rating">Rating</label>
            <input type="range" name="rating" id="rating" value="0" min="0" max="10" required><span id="rating-value">0</span>
        </div>

        <div>
            <label for="description">Description</label>
            <input type="text" name="description" id="description" autocomplete="off" required>
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