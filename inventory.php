<?php
require_once "auth.php";
require_once "audit.php";

require_once "config.php";
$conn = monitor_db();

$result = $conn->query(
    "SELECT
        ID,
        PLU,
        DESCRIPTION,
        SPRICE,
        CPRICE,
        ONHAND,
        TAX_STATUS,
        DEPT_NAME,
        SUPPLIER_ID,
        SAFETY_STOCK,
        REORDER_PT
     FROM inventory
     ORDER BY DESCRIPTION ASC"
);

if (!$result) {
    die("Inventory error: " . $conn->error);
}

$page = "Inventory";

include "template_top.php";
?>

<div style="
display:flex;
justify-content:space-between;
align-items:center;
flex-wrap:wrap;
gap:12px;
">

<div>

<h2>Inventory</h2>

<p class="subtitle">
Manage products and stock remotely.
</p>

</div>

<div>

<a
href="inventory_new.php"
style="
background:#bdebf3;
padding:11px 18px;
border-radius:6px;
text-decoration:none;
color:#263238;
font-weight:bold;
"
>
+ New Item
</a>

<a
href="inventory_adjustments.php"
style="
background:#eef7fa;
padding:11px 18px;
border-radius:6px;
text-decoration:none;
color:#263238;
margin-left:5px;
"
>
Stock History
</a>

</div>

</div>


<input
class="search"
id="search"
type="text"
placeholder="Search product, code or department..."
onkeyup="searchTable()"
>


<div class="table-box">

<table id="dataTable">

<thead>

<tr>

<th>Code</th>
<th>Description</th>
<th>Price</th>
<th>Cost</th>
<th>On Hand</th>
<th>Tax</th>
<th>Department</th>
<th>Supplier</th>
<th>Actions</th>

</tr>

</thead>


<tbody>


<?php

while ($row = $result->fetch_assoc()) {

    $onHand =
        floatval($row["ONHAND"]);

    $safety =
        floatval($row["SAFETY_STOCK"]);

    $low =
        ($safety > 0 && $onHand <= $safety);

?>

<tr
<?php

if ($low) {
    echo 'style="background:#fff8e1;"';
}

?>
>

<td>

<strong>
<?php echo htmlspecialchars($row["PLU"]); ?>
</strong>

</td>


<td>

<?php echo htmlspecialchars($row["DESCRIPTION"]); ?>

<?php if ($low) { ?>

<br>

<small style="color:#d84315;">
⚠ Low Stock
</small>

<?php } ?>

</td>


<td>
<?php echo number_format(floatval($row["SPRICE"]),2); ?>
</td>


<td>
<?php echo number_format(floatval($row["CPRICE"]),2); ?>
</td>


<td>

<strong>
<?php echo number_format($onHand,3); ?>
</strong>

</td>


<td>
<?php echo htmlspecialchars($row["TAX_STATUS"]); ?>
</td>


<td>
<?php echo htmlspecialchars($row["DEPT_NAME"]); ?>
</td>


<td>
<?php echo intval($row["SUPPLIER_ID"]); ?>
</td>


<td style="white-space:nowrap;">

<a
href="inventory_receive.php?id=<?php echo intval($row["ID"]); ?>"
style="
background:#bdebf3;
padding:7px 10px;
border-radius:5px;
text-decoration:none;
color:#263238;
"
>
Receive
</a>


<a
href="inventory_edit.php?id=<?php echo intval($row["ID"]); ?>"
style="
background:#eef1f2;
padding:7px 10px;
border-radius:5px;
text-decoration:none;
color:#263238;
"
>
Edit
</a>

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