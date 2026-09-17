<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed.");
}

$selectedDate = isset($_GET["date"])
    ? $_GET["date"]
    : date("Y-m-d");

$selectedDate = $conn->real_escape_string($selectedDate);

$sql = "
SELECT
    REC_NO,
    SALES_DATE,
    SALES_TIME,
    SALES_PERSON,
    PLU,
    DESCRIPTION,
    QUANTITY,
    PRICE,
    DISCOUNT,
    TOTAL,
    PAY_METHOD,
    STATUS
FROM sales
WHERE SALES_DATE = '$selectedDate'
ORDER BY SALES_TIME DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Daily sales error: " . $conn->error);
}

$page = "Reports";
include "template_top.php";
?>

<h2>Itemized Daily Sales</h2>

<p class="subtitle">
Every item sold on the selected date.
</p>

<form method="GET" style="margin-bottom:20px;">

<input
type="date"
name="date"
value="<?php echo htmlspecialchars($selectedDate); ?>"
style="padding:8px;"
>

<button type="submit"
style="
padding:9px 16px;
background:#bdebf3;
border:1px solid #8fcbd5;
border-radius:5px;
">
View
</button>

</form>

<div class="table-box">

<table>

<thead>
<tr>
<th>Receipt</th>
<th>Date</th>
<th>Time</th>
<th>Cashier</th>
<th>PLU</th>
<th>Description</th>
<th>Qty</th>
<th>Price</th>
<th>Discount</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
</tr>
</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()) { ?>

<tr>

<td><?php echo htmlspecialchars($row["REC_NO"]); ?></td>
<td><?php echo htmlspecialchars($row["SALES_DATE"]); ?></td>
<td><?php echo htmlspecialchars($row["SALES_TIME"]); ?></td>
<td><?php echo htmlspecialchars($row["SALES_PERSON"]); ?></td>
<td><?php echo htmlspecialchars($row["PLU"]); ?></td>
<td><?php echo htmlspecialchars($row["DESCRIPTION"]); ?></td>

<td>
<?php echo number_format((float)$row["QUANTITY"],3); ?>
</td>

<td>
<?php echo number_format((float)$row["PRICE"],2); ?>
</td>

<td>
<?php echo number_format((float)$row["DISCOUNT"],2); ?>
</td>

<td>
<strong>
<?php echo number_format((float)$row["TOTAL"],2); ?>
</strong>
</td>

<td><?php echo htmlspecialchars($row["PAY_METHOD"]); ?></td>
<td><?php echo htmlspecialchars($row["STATUS"]); ?></td>

</tr>

<?php } ?>

</tbody>
</table>

</div>

<?php
include "template_bottom.php";
$conn->close();
?>
