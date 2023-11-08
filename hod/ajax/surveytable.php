<?php
require "../../functions.php";
if(isset($_POST['area'])) {
    $area = $_POST['area'];
} else {
    $area = 'all';
}
$district = $_POST['district'];
$results = getSurvey($district, $area);
?>


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
<?php $i++; } ?>

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