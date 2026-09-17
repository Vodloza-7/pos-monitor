<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed.");
}

$sql = "
SELECT
    YEAR(SALES_DATE) AS SALES_YEAR,
    MONTH(SALES_DATE) AS SALES_MONTH,
    PAY_METHOD,
    COUNT(DISTINCT REC_NO) AS TRANSACTIONS,
    SUM(QUANTITY) AS ITEMS_SOLD,
    SUM(TOTAL) AS TOTAL_SALES
FROM sales
WHERE STATUS = 'COMPLETED'
GROUP BY
    YEAR(SALES_DATE),
    MONTH(SALES_DATE),
    PAY_METHOD
ORDER BY
    SALES_YEAR DESC,
    SALES_MONTH DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Monthly report error: " . $conn->error);
}

$page = "Reports";
include "template_top.php";
?>

<h2>Month-on-Month Sales</h2>

<p class="subtitle">
Compare business performance across months.
</p>

<div class="table-box">

<table>

<thead>
<tr>
<th>Month</th>
<th>Currency / Method</th>
<th>Transactions</th>
<th>Items Sold</th>
<th>Total Sales</th>
</tr>
</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()) { ?>

<tr>

<td>
<strong>
<?php

echo date(
    "F Y",
    mktime(
        0,
        0,
        0,
        intval($row["SALES_MONTH"]),
        1,
        intval($row["SALES_YEAR"])
    )
);

?>
</strong>
</td>

<td>
<?php echo htmlspecialchars($row["PAY_METHOD"]); ?>
</td>

<td>
<?php echo intval($row["TRANSACTIONS"]); ?>
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
