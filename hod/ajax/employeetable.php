<?php
require "../../functions.php";
if(isset($_POST['area'])) {
    $area = $_POST['area'];
} else {
    $area = 'all';
}
$district = $_POST['district'];
$results = getEmployee($district, $area);
?>


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

<script>
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