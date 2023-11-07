<?php
require "../functions.php";
session_start();
if(!isset($_SESSION['loginId'])) {
    jumpTo('../');
}
$results = getSurvey();
$districts = getOutletDistrict();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD SURVEY</title>
    <script src="../src/js/jquery-3.5.1.min.js"></script>
</head>
<body>
    <h1>HOD SURVEY</h1>

    <div>
        <div>
            <label for="district">District:</label>
            <select name="district" id="district">
                <option value="all" selected>--Choose--</option>
                <?php foreach($districts as $district) { ?>
                    <option value="<?php echo $district['district'] ?>"><?php echo $district['district'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label for="area">Area:</label>
            <select name="area" id="area">
                <option value="all" selected>--Choose--</option>  
            </select>
        </div>
    </div>

    <table border="1" id="survey-table">
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
    </table>
    <script>
        $("#district").change(function(e) {
            var district = $(this).val();
            console.log(district);
            $.ajax({
                type: "POST",
                url: "ajax/outletarea.php",
                data: {district: district},
                success: function(result) {
                    $("#area").html(result);
                }
            });
            $.ajax({
                type: "POST",
                url: "ajax/surveytable.php",
                data: {district: district},
                success: function(result) {
                    $("#survey-table").html(result);
                }
            });
            $("#area").change(function() {
                var area = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "ajax/surveytable.php",
                    data: {area: area, district: district},
                    success: function(result) {
                        $("#survey-table").html(result);
                    }
                });
            });
        });
        
    </script>
    <script>
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
        const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
            v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
            )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

        // do the work...
        document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
            const table = th.closest('table');
            Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
                .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                .forEach(tr => table.appendChild(tr) );
        })));
    </script>
</body>
</html>