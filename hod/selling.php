<?php
require "../functions.php";
session_start();
if(!isset($_SESSION['loginId'])) {
    jumpTo('../');
}
$districts = getOutletDistrict();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD SELLING</title>
    <script src="../src/js/jquery-3.5.1.min.js"></script>
</head>
<body>
    <h1>HOD SELLING</h1>
    <div>
        <div>
            <label for="district">District:</label>
            <select name="district" id="district">
                <option value="" selected>--Choose--</option>
                <?php foreach($districts as $district) { ?>
                    <option value="<?php echo $district['district'] ?>"><?php echo $district['district'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label for="area">Area:</label>
            <select name="area" id="area">
                <option value="" selected>--Choose--</option>  
            </select>
        </div>
        <div>
            <label for="outlet">Outlet:</label>
            <select name="outlet" id="outlet">
                <option value="" selected>--Choose--</option>  
            </select>
        </div>
    </div>

    <div id="selling-table-container">

    </div>
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
            // $.ajax({
            //     type: "POST",
            //     url: "ajax/employeetable.php",
            //     data: {district: district},
            //     success: function(result) {
            //         $("#employee-table").html(result);
            //     }
            // });
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
                // $.ajax({
                //     type: "POST",
                //     url: "ajax/employeetable.php",
                //     data: {area: area, district: district},
                //     success: function(result) {
                //         $("#employee-table").html(result);
                //     }
                // });
                $("#outlet").change(function() {
                    var outlet = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "ajax/sellingtable.php",
                        data: {area: area, district: district, outlet: outlet},
                        success: function(result) {
                            $("#selling-table-container").html(result);
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
    </script>
</body>
</html>