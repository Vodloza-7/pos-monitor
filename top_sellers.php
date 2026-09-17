<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed.");
}

$sql = "
SELECT
    PLU,
    DESCRIPTION,
    PAY_METHOD,
    SUM(QUANTITY) AS QUANTITY_SOLD,
    SUM(TOTAL) AS REVENUE
FROM sales
WHERE STATUS = 'COMPLETED'
GROUP BY PLU, DESCRIPTION, PAY_METHOD
ORDER BY QUANTITY_SOLD DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Top sellers error: " . $conn->error);
}

$page = "Reports";
include "template_top.php";
?>

<h2>Top Sellers</h2>

<p class="subtitle">
Best-selling products by quantity.
</p>

<div class="table-box">

<table>

<thead>
<tr>
<th>Rank</th>
<th>PLU</th>
<th>Product</th>
<th>Currency / Method</th>
<th>Quantity Sold</th>
<th>Revenue</th>
</tr>
</thead>

<tbody>

<?php

$rank = 1;

while ($row = $result->fetch_assoc()) {

?>

<tr>

<td>
<strong>#<?php echo $rank; ?></strong>
</td>

<td><?php echo htmlspecialchars($row["PLU"]); ?></td>

<td>
<strong>
<?php echo htmlspecialchars($row["DESCRIPTION"]); ?>
</strong>
</td>

<td><?php echo htmlspecialchars($row["PAY_METHOD"]); ?></td>

<td>
<?php echo number_format((float)$row["QUANTITY_SOLD"],3); ?>
</td>

<td>
<?php echo number_format((float)$row["REVENUE"],2); ?>
</td>

</tr>

<?php

$rank++;

}

?>

</tbody>

</table>

</div>

<?php
include "template_bottom.php";
$conn->close();
?>