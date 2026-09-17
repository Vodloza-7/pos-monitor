<?php
require_once "auth.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed.");
}

$result = $conn->query(
    "SELECT *
     FROM sales
     ORDER BY SALES_DATE DESC,
              SALES_TIME DESC,
              ID DESC"
);

if (!$result) {
    die("Sales log error: " . $conn->error);
}

$page = "Reports";

include "template_top.php";
?>

<h2>Sales Log</h2>

<p class="subtitle">
Complete raw sales history recorded by the POS.
</p>

<input
class="search"
id="search"
type="text"
placeholder="Search receipt, product, cashier, payment..."
onkeyup="searchTable()"
>

<div class="table-box">

<table id="dataTable">

<thead>

<tr>

<?php

while ($field = $result->fetch_field()) {

    echo "<th>" .
         htmlspecialchars($field->name) .
         "</th>";
}

?>

</tr>

</thead>

<tbody>

<?php

while ($row = $result->fetch_assoc()) {

    echo "<tr>";

    foreach ($row as $value) {

        echo "<td>" .
             htmlspecialchars((string)$value) .
             "</td>";
    }

    echo "</tr>";
}

?>

</tbody>

</table>

</div>

<?php

include "template_bottom.php";

$conn->close();

?>