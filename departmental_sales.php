<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed.");
}

$sql = "
SELECT
    DEPT_NAME,
    PAY_METHOD,
    SUM(QUANTITY) AS ITEMS_SOLD,
    SUM(TOTAL) AS TOTAL_SALES
FROM sales
WHERE STATUS = 'COMPLETED'
GROUP BY DEPT_NAME, PAY_METHOD
ORDER BY TOTAL_SALES DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Department report error: " . $conn->error);
}

$page = "Reports";
include "template_top.php";
?>

<h2>Department Sales</h2>

<p class="subtitle">
Sales performance by department.
</p>

<div class="table-box">

<table>

<thead>
<tr>
<th>Department</th>
<th>Currency / Method</th>
<th>Items Sold</th>
<th>Sales Total</th>
</tr>
</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()) { ?>

<tr>

<td>
<?php
echo htmlspecialchars(
    $row["DEPT_NAME"] == ""
    ? "Unassigned"
    : $row["DEPT_NAME"]
);
?>
</td>

<td>
<?php echo htmlspecialchars($row["PAY_METHOD"]); ?>
</td>

<td>
<?php echo number_format((float)$row["ITEMS_SOLD"],3); ?>
</td>

<td>
<strong>
<?php echo number_format((float)$row["TOTAL_SALES"],2); ?>
</strong>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php
include "template_bottom.php";
$conn->close();
?>
