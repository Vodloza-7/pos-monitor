<?php
require_once "auth.php";
require_once "config.php";
$conn = monitor_db();

$result =
    $conn->query(
        "SELECT * FROM clientdetails LIMIT 100"
    );

if (!$result) {

    die(
        "Clients error: "
        . $conn->error
    );

}

$page = "Clients";

include "template_top.php";

?>

<h2>Clients</h2>

<p class="subtitle">
View customers registered in the POS.
</p>

<input
class="search"
id="search"
type="text"
placeholder="Search clients..."
onkeyup="searchTable()"
>

<div class="table-box">

<table id="dataTable">

<thead>

<tr>

<?php

while ($field = $result->fetch_field()) {

    echo "<th>"
        . htmlspecialchars($field->name)
        . "</th>";

}

?>

</tr>

</thead>

<tbody>

<?php

while ($row = $result->fetch_assoc()) {

    echo "<tr>";

    foreach ($row as $value) {

        echo "<td>"
            . htmlspecialchars(
                (string)$value
            )
            . "</td>";

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