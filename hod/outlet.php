<?php
require "../functions.php";
session_start();
if(!isset($_SESSION['loginId'])) {
    jumpTo('../');
}
$outlets = getOutlet();
$districts = getOutletDistrict();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../src/js/jquery-3.5.1.min.js"></script>
    <title>HOD OUTLET</title>
</head>
<body>
    <h1>HOD OUTLET</h1>
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

    <table border="1" id="outlet-table">
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
    </table>
    <script>
        $("#district").change(function(e) {
            var district = $(this).val();
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
                url: "ajax/outlettable.php",
                data: {district: district},
                success: function(result) {
                    $("#outlet-table").html(result);
                }
            });
            $("#area").change(function() {
                var area = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "ajax/outlettable.php",
                    data: {area: area, district: district},
                    success: function(result) {
                        $("#outlet-table").html(result);
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