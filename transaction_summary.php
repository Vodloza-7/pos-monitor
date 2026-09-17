<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed.");
}

$sql = "
SELECT
    REC_NO,
    SALES_DATE,
    SALES_TIME,
    SALES_PERSON,
    PAY_METHOD,
    STATUS,
    SUM(QUANTITY) AS ITEMS,
    SUM(TOTAL) AS TRANSACTION_TOTAL
FROM sales
GROUP BY
    REC_NO,
    SALES_DATE,
    SALES_TIME,
    SALES_PERSON,
    PAY_METHOD,
    STATUS
ORDER BY SALES_DATE DESC, SALES_TIME DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Transaction report error: " . $conn->error);
}

$page = "Reports";
include "template_top.php";
?>

<h2>Transaction Summary</h2>

<p class="subtitle">
Summary of transactions recorded by the POS.
</p>

<div class="table-box">

<table>

<thead>
<tr>
<th>Receipt</th>
<th>Date</th>
<th>Time</th>
<th>Cashier</th>
<th>Items</th>
<th>Payment</th>
<th>Status</th>
<th>Total</th>
</tr>
</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()) { ?>

<tr>

<td><?php echo htmlspecialchars($row["REC_NO"]); ?></td>
<td><?php echo htmlspecialchars($row["SALES_DATE"]); ?></td>
<td><?php echo htmlspecialchars($row["SALES_TIME"]); ?></td>
<td><?php echo htmlspecialchars($row["SALES_PERSON"]); ?></td>

<td>
<?php echo number_format((float)$row["ITEMS"],3); ?>
</td>

<td><?php echo htmlspecialchars($row["PAY_METHOD"]); ?></td>

<td><?php echo htmlspecialchars($row["STATUS"]); ?></td>

<td>
<strong>
<?php echo number_format((float)$row["TRANSACTION_TOTAL"],2); ?>
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
