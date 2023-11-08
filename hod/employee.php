<?php
require "../functions.php";
session_start();
if(!isset($_SESSION['loginId'])) {
    jumpTo('../');
}
$results = getEmployee();
$districts = getOutletDistrict();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD EMPLOYEE</title>
    <script src="../src/js/jquery-3.5.1.min.js"></script>
</head>
<body>
    <h1>HOD EMPLOYEE</h1>
    <a href="employee/">Add Employee</a>
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
        <div>
            <label for="outlet">Outlet:</label>
            <select name="outlet" id="outlet">
                <option value="all" selected>--Choose--</option>  
            </select>
        </div>
    </div>

    <table border="1" id="employee-table">
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
    </table>
    <script>
        $("#district").change(function() {
            var district = $(this).val();
            if(district == "all") {
                $("#outlet").val("all").change();
            }
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
                url: "ajax/employeetable.php",
                data: {district: district},
                success: function(result) {
                    $("#employee-table").html(result);
                }
            });
            $("#area").change(function() {
                var area = $(this).val();
                
                $.ajax({
                    type: "POST",
                    url: "ajax/outletoutlet.php",
                    data: {area: area, district: district},
                    success: function(result) {
                        $("#outlet").html(result);
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "ajax/employeetable.php",
                    data: {area: area, district: district},
                    success: function(result) {
                        $("#employee-table").html(result);
                    }
                });
                $("#outlet").change(function() {
                    var outlet = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "ajax/employeetable.php",
                        data: {area: area, district: district, outlet: outlet},
                        success: function(result) {
                            $("#employee-table").html(result);
                        }
                    });
                });
            });
        });
        
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

        $('.fire-button').click(function() {
            id = $(this).val();
            var confirmation = confirm('Fire ' + id + '?');
            if(confirmation == true) {
                $.ajax({
                    type: "POST",
                    url: "ajax/employeefire.php",
                    data: {id: id},
                    success: function() {
                        alert(id + ' is fired successfully');
                        location.reload();
                    }
                });
            };
        });
    </script>
</body>
</html>