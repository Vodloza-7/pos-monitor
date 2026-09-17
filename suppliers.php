<?php
require_once "auth.php";
require_once "config.php";
$conn = monitor_db();

$message = "Vodloza";
$error = "";


/* ==========================================
   ADD NEW SUPPLIER
   ========================================== */

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST["add_supplier"])
) {

    $name = isset($_POST["sup_name"])
        ? trim($_POST["sup_name"])
        : "";

    $company = isset($_POST["sup_company"])
        ? trim($_POST["sup_company"])
        : "";

    $phone = isset($_POST["phone"])
        ? trim($_POST["phone"])
        : "";

    $address = isset($_POST["address"])
        ? trim($_POST["address"])
        : "";


    if ($name == "") {

        $error = "Please enter faka the supplier name.";

    } else {

        /*
         * Escape text before inserting.
         * Compatible with your older PHP/XAMPP.
         */

        $name =
            $conn->real_escape_string($name);

        $company =
            $conn->real_escape_string($company);

        $phone =
            $conn->real_escape_string($phone);

        $address =
            $conn->real_escape_string($address);


        $sql = "
        INSERT INTO suppliers
        (
            SUP_NAME,
            SUP_COMPANY,
            PHONE,
            ADDRESS
        )
        VALUES
        (
            '$name',
            '$company',
            '$phone',
            '$address'
        )
        ";


        if ($conn->query($sql)) {

            $message =
                "Supplier added successfully.";

        } else {

            $error =
                "Could not add supplier: " .
                $conn->error;
        }
    }
}


/* ==========================================
   LOAD SUPPLIERS
   ========================================== */

$result = $conn->query(
    "SELECT
        SUPPLIER_ID,
        SUP_NAME,
        SUP_COMPANY,
        PHONE,
        ADDRESS
     FROM suppliers
     ORDER BY SUP_NAME ASC"
);


if (!$result) {

    die(
        "Unable to load suppliers: " .
        $conn->error
    );
}


$page = "Suppliers";

include "template_top.php";

?>


<h2>Suppliers</h2>

<p class="subtitle">
Manage suppliers connected to the POS.
</p>


<!-- SUCCESS -->

<?php if ($message != "") { ?>

<div style="
    background:#e8f5e9;
    border:1px solid #a5d6a7;
    padding:14px;
    margin-bottom:18px;
    border-radius:7px;
">

<strong>✓ Success</strong>

<br>

<?php
echo htmlspecialchars($message);
?>

</div>

<?php } ?>


<!-- ERROR -->

<?php if ($error != "") { ?>

<div style="
    background:#ffebee;
    border:1px solid #ef9a9a;
    padding:14px;
    margin-bottom:18px;
    border-radius:7px;
">

<strong>Unable to add supplier</strong>

<br>

<?php
echo htmlspecialchars($error);
?>

</div>

<?php } ?>


<!-- ==========================================
     ADD SUPPLIER FORM
     ========================================== -->

<div style="
    background:white;
    border:1px solid #dce8eb;
    border-radius:10px;
    padding:22px;
    margin-bottom:25px;
">

<h3 style="margin-top:0;">
+ Add Supplier
</h3>


<form method="POST" action="suppliers.php">


<div style="
    display:grid;
    grid-template-columns:
        repeat(auto-fit,minmax(200px,1fr));
    gap:14px;
">


<div>

<label>
<strong>Supplier Name *</strong>
</label>

<br>

<input
    type="text"
    name="sup_name"
    required
    placeholder="e.g. John Dube"
    style="
        width:100%;
        padding:10px;
        margin-top:5px;
        border:1px solid #bbb;
        border-radius:5px;
    "
>

</div>


<div>

<label>
<strong>Company</strong>
</label>

<br>

<input
    type="text"
    name="sup_company"
    placeholder="e.g. ABC Distributors"
    style="
        width:100%;
        padding:10px;
        margin-top:5px;
        border:1px solid #bbb;
        border-radius:5px;
    "
>

</div>


<div>

<label>
<strong>Phone</strong>
</label>

<br>

<input
    type="text"
    name="phone"
    placeholder="+263..."
    style="
        width:100%;
        padding:10px;
        margin-top:5px;
        border:1px solid #bbb;
        border-radius:5px;
    "
>

</div>


<div>

<label>
<strong>Address</strong>
</label>

<br>

<input
    type="text"
    name="address"
    placeholder="Supplier address"
    style="
        width:100%;
        padding:10px;
        margin-top:5px;
        border:1px solid #bbb;
        border-radius:5px;
    "
>

</div>


</div>


<br>


<button
    type="submit"
    name="add_supplier"
    value="1"
    style="
        background:#bdebf3;
        border:1px solid #8fcbd5;
        padding:11px 20px;
        border-radius:6px;
        cursor:pointer;
        font-weight:bold;
    "
>

+ Add Supplier

</button>


</form>

</div>


<!-- ==========================================
     SUPPLIER LIST
     ========================================== -->

<h3>
Registered Suppliers
</h3>


<input
    class="search"
    id="search"
    type="text"
    placeholder="Search supplier..."
    onkeyup="searchTable()"
>


<?php

if ($result->num_rows == 0) {

?>


<div style="
    background:white;
    border:1px solid #dce8eb;
    border-radius:10px;
    padding:35px;
    text-align:center;
">

<div style="
    font-size:40px;
">
📦
</div>

<h3>
No Suppliers Yet
</h3>

<p style="color:#607d8b;">

No suppliers have been registered
in the POS database.

</p>

<p style="color:#607d8b;">

Use the form above to add your first supplier.

</p>

</div>


<?php

} else {

?>


<div class="table-box">

<table id="dataTable">

<thead>

<tr>

<th>ID</th>
<th>Supplier</th>
<th>Company</th>
<th>Phone</th>
<th>Address</th>

</tr>

</thead>


<tbody>


<?php

while (
    $row = $result->fetch_assoc()
) {

?>


<tr>


<td>

<?php
echo intval(
    $row["SUPPLIER_ID"]
);
?>

</td>


<td>

<strong>

<?php
echo htmlspecialchars(
    $row["SUP_NAME"]
);
?>

</strong>

</td>


<td>

<?php
echo htmlspecialchars(
    $row["SUP_COMPANY"]
);
?>

</td>


<td>

<?php
echo htmlspecialchars(
    $row["PHONE"]
);
?>

</td>


<td>

<?php
echo htmlspecialchars(
    $row["ADDRESS"]
);
?>

</td>


</tr>


<?php

}

?>


</tbody>

</table>

</div>


<?php

}


/* ==========================================
   TEMPLATE
   ========================================== */

include "template_bottom.php";

$conn->close();

?>