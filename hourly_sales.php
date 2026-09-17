<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$selectedDate = isset($_GET["date"])
    ? $_GET["date"]
    : date("Y-m-d");

$selectedDate = $conn->real_escape_string($selectedDate);

$sql = "
SELECT
    HOUR(SALES_TIME) AS SALE_HOUR,
    PAY_METHOD,
    COUNT(DISTINCT REC_NO) AS TRANSACTIONS,
    SUM(QUANTITY) AS ITEMS_SOLD,
    SUM(TOTAL) AS TOTAL_SALES
FROM sales
WHERE SALES_DATE = '$selectedDate'
AND STATUS = 'COMPLETED'
GROUP BY HOUR(SALES_TIME), PAY_METHOD
ORDER BY SALE_HOUR ASC, PAY_METHOD ASC
";

$result = $conn->query($sql);

if (!$result) {
    die("Hourly sales error: " . $conn->error);
}

$page = "Reports";
include "template_top.php";
?>

<h2>Hourly Sales</h2>

<p class="subtitle">
    Sales performance by hour.
</p>

<form method="GET" style="margin-bottom:20px;">

    <label>
        <strong>Select Date:</strong>
    </label>

    <input
        type="date"
        name="date"
        value="<?php echo htmlspecialchars($selectedDate); ?>"
        style="padding:8px;"
    >

    <button
        type="submit"
        style="
            padding:9px 16px;
            background:#bdebf3;
            border:1px solid #8fcbd5;
            border-radius:5px;
        "
    >
        View Report
    </button>

</form>

<div class="table-box">

<table>

<thead>
<tr>
    <th>Hour</th>
    <th>Currency / Method</th>
    <th>Transactions</th>
    <th>Items Sold</th>
    <th>Sales Total</th>
</tr>
</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()) { ?>

<tr>

<td>
<?php
echo sprintf(
    "%02d:00 - %02d:59",
    $row["SALE_HOUR"],
    $row["SALE_HOUR"]
);
?>
</td>

<td>
<strong>
<?php echo htmlspecialchars($row["PAY_METHOD"]); ?>
</strong>
</td>

<td>
<?php echo intval($row["TRANSACTIONS"]); ?>
</td>

<td>
<?php echo number_format((float)$row["ITEMS_SOLD"], 3); ?>
</td>

<td>
<strong>
<?php echo number_format((float)$row["TOTAL_SALES"], 2); ?>
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