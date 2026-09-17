<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM sales LIMIT 100");

if (!$result) {
    die("Sales error: " . $conn->error);
}

$page = "Sales";
?>

<?php include "template_top.php"; ?>

<h2>Sales</h2>
<p class="subtitle">Monitor transactions from the POS.</p>

<input
    class="search"
    type="text"
    id="search"
    placeholder="Search sales..."
    onkeyup="searchTable()"
>

<div class="table-box">

<table id="dataTable">

<thead>
<tr>

<?php
while ($field = $result->fetch_field()) {
    echo "<th>" . htmlspecialchars($field->name) . "</th>";
}
?>

</tr>
</thead>

<tbody>

<?php
while ($row = $result->fetch_assoc()) {

    echo "<tr>";

    foreach ($row as $value) {
        echo "<td>" . htmlspecialchars((string)$value) . "</td>";
    }

    echo "</tr>";
}
?>

</tbody>

</table>

</div>

<?php include "template_bottom.php"; ?>

<?php $conn->close(); ?>