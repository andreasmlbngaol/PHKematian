<?php
require "../../functions.php";
if(isset($_POST['area'])) {
    $area = $_POST['area'];
} else {
    $area = 'all';
}
$district = $_POST['district'];
$outlets = getOutlet($district, $area);
?>

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