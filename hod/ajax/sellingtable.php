<?php
require "../../functions.php";
$district = $_POST['district'];
$area = $_POST['area'];
$outlet = $_POST['outlet'];
$results = getSellingOutlet($district, $area, $outlet);
if($results != NULL) {
?>

<table border="1">
    <tr>
        <th>Date</th>
        <th>Outlet</th>
        <th>Area</th>
        <th>District</th>
        <th>Dine In</th>
        <th>Delivery</th>
        <th>Cost</th>
        <th>Income</th>
        <th>Profit</th>
    </tr>
    <?php foreach($results as $result) { ?>
    <tr>
        <td><?php echo $result['date'] ?></td>
        <td><?php echo $result['outlet'] ?></td>
        <td><?php echo $result['area'] ?></td>
        <td><?php echo $result['district'] ?></td>
        <td><?php echo $result['dine_in'] ?></td>
        <td><?php echo $result['delivery'] ?></td>
        <td><?php echo $result['cost'] ?></td>
        <td><?php echo $result['income'] ?></td>
        <td><?php echo $result['profit'] ?></td>
    </tr>
    <?php } ?>

</table>
<?php } ?>

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