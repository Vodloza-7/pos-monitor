<?php
require_once "auth.php";
require_once "audit.php";
require_once "config.php";
$conn = monitor_db();

$id = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;

$message = "";
$error = "";


/* UPDATE PRODUCT */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id =
        intval($_POST["product_id"]);

    $description =
        $conn->real_escape_string(
            trim($_POST["description"])
        );

    $sellingPrice =
        floatval($_POST["selling_price"]);

    $costPrice =
        floatval($_POST["cost_price"]);

    $taxStatus =
        $conn->real_escape_string(
            $_POST["tax_status"]
        );

    $department =
        $conn->real_escape_string(
            trim($_POST["department"])
        );

    $supplierId =
        intval($_POST["supplier_id"]);

    $safetyStock =
        floatval($_POST["safety_stock"]);

    $reorderPoint =
        floatval($_POST["reorder_point"]);

    $reorderQty =
        floatval($_POST["reorder_qty"]);

    $caseQty =
        intval($_POST["case_qty"]);

    $binLocation =
        $conn->real_escape_string(
            trim($_POST["bin_location"])
        );


    if ($description == "") {

        $error =
            "Description is required.";

    } else {

        $sql = "
        UPDATE inventory
        SET
            DESCRIPTION = '$description',
            SPRICE = $sellingPrice,
            CPRICE = $costPrice,
            TAX_STATUS = '$taxStatus',
            DEPT_NAME = '$department',
            SUPPLIER_ID = $supplierId,
            SAFETY_STOCK = $safetyStock,
            REORDER_PT = $reorderPoint,
            REORDER_QTY = $reorderQty,
            CASE_QTY = $caseQty,
            BINLOCATION = '$binLocation'
        WHERE ID = $id
        ";


        if ($conn->query($sql)) {

            $message =
                "Product details updated successfully.";
                 writeAudit(
                    $conn,
                   "PRODUCT_EDITED",
                   $_SESSION["monitor_full_name"] .
                   " changed the details of " .
                   $description .
                   "."
                  );

        } else {

            $error =
                "Update failed: " .
                $conn->error;
        }
    }
}


/* LOAD PRODUCT */

$productResult =
    $conn->query(
        "SELECT *
         FROM inventory
         WHERE ID = $id
         LIMIT 1"
    );


if (!$productResult ||
    $productResult->num_rows == 0) {

    die("Product not found.");
}


$product =
    $productResult->fetch_assoc();


/* LOAD SUPPLIERS */

$suppliers =
    $conn->query(
        "SELECT
            SUPPLIER_ID,
            SUP_NAME,
            SUP_COMPANY
         FROM suppliers
         ORDER BY SUP_NAME ASC"
    );


$page = "Inventory";

include "template_top.php";

?>


<h2>Edit Product</h2>

<p class="subtitle">

<?php
echo htmlspecialchars(
    $product["PLU"]
);
?>

—

<?php
echo htmlspecialchars(
    $product["DESCRIPTION"]
);
?>

</p>


<?php if ($message != "") { ?>

<div style="
background:#e8f5e9;
padding:15px;
border-radius:7px;
margin-bottom:18px;
">

✓ <?php echo htmlspecialchars($message); ?>

</div>

<?php } ?>


<?php if ($error != "") { ?>

<div style="
background:#ffebee;
padding:15px;
border-radius:7px;
margin-bottom:18px;
">

<?php echo htmlspecialchars($error); ?>

</div>

<?php } ?>


<div style="
background:white;
padding:24px;
border-radius:10px;
border:1px solid #dce8eb;
">


<form method="POST">


<input
type="hidden"
name="product_id"
value="<?php echo $id; ?>"
>


<div style="
display:grid;
grid-template-columns:
repeat(auto-fit,minmax(210px,1fr));
gap:16px;
">


<div>

<label>
<strong>Stock Code</strong>
</label>

<input
type="text"
value="<?php
echo htmlspecialchars(
    $product["PLU"]
);
?>"
disabled
style="
width:100%;
padding:10px;
margin-top:5px;
background:#eee;
"
>

</div>


<div>

<label>
<strong>Description</strong>
</label>

<input
type="text"
name="description"
value="<?php
echo htmlspecialchars(
    $product["DESCRIPTION"]
);
?>"
required
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Selling Price</strong>
</label>

<input
type="text"
name="selling_price"
value="<?php
echo htmlspecialchars(
    $product["SPRICE"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Cost Price</strong>
</label>

<input
type="text"
name="cost_price"
value="<?php
echo htmlspecialchars(
    $product["CPRICE"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Current Stock</strong>
</label>

<input
type="text"
value="<?php
echo htmlspecialchars(
    $product["ONHAND"]
);
?>"
disabled
style="
width:100%;
padding:10px;
margin-top:5px;
background:#eee;
"
>

<small>
Use Receive Stock to change quantity.
</small>

</div>


<div>

<label>
<strong>Tax Status</strong>
</label>

<select
name="tax_status"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

<option
value="NON-TAX"
<?php

if (
    $product["TAX_STATUS"] ==
    "NON-TAX"
) {
    echo " selected";
}

?>
>
NON-TAX
</option>


<option
value="VAT"
<?php

if (
    $product["TAX_STATUS"] ==
    "VAT"
) {
    echo " selected";
}

?>
>
VAT
</option>

</select>

</div>


<div>

<label>
<strong>Department</strong>
</label>

<input
type="text"
name="department"
value="<?php
echo htmlspecialchars(
    $product["DEPT_NAME"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Supplier</strong>
</label>

<select
name="supplier_id"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

<option value="0">
No Supplier
</option>


<?php

if ($suppliers) {

    while (
        $supplier =
        $suppliers->fetch_assoc()
    ) {

        $sid =
            intval(
                $supplier[
                    "SUPPLIER_ID"
                ]
            );

?>

<option
value="<?php echo $sid; ?>"

<?php

if (
    $sid ==
    intval(
        $product["SUPPLIER_ID"]
    )
) {

    echo " selected";
}

?>
>

<?php

echo htmlspecialchars(
    $supplier["SUP_NAME"]
);

?>

</option>

<?php

    }
}

?>

</select>

</div>


<div>

<label>
<strong>Safety Stock</strong>
</label>

<input
type="text"
name="safety_stock"
value="<?php
echo htmlspecialchars(
    $product["SAFETY_STOCK"]
);
?>"
style="
width:100%;
padding:

<div>

<label>
<strong>Safety Stock</strong>
</label>

<input
type="text"
name="safety_stock"
value="<?php
echo htmlspecialchars(
    $product["SAFETY_STOCK"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Reorder Point</strong>
</label>

<input
type="text"
name="reorder_point"
value="<?php
echo htmlspecialchars(
    $product["REORDER_PT"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Reorder Quantity</strong>
</label>

<input
type="text"
name="reorder_qty"
value="<?php
echo htmlspecialchars(
    $product["REORDER_QTY"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Case Quantity</strong>
</label>

<input
type="text"
name="case_qty"
value="<?php
echo htmlspecialchars(
    $product["CASE_QTY"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Bin Location</strong>
</label>

<input
type="text"
name="bin_location"
value="<?php
echo htmlspecialchars(
    $product["BINLOCATION"]
);
?>"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


</div>

<br>


<button
type="submit"
style="
background:#bdebf3;
border:1px solid #8fcbd5;
padding:12px 24px;
border-radius:6px;
font-weight:bold;
cursor:pointer;
"
>

Save Changes

</button>


<a
href="inventory.php"
style="
display:inline-block;
margin-left:12px;
padding:12px 20px;
text-decoration:none;
color:#455a64;
"
>

Cancel

</a>


<a
href="inventory_receive.php?id=<?php echo $id; ?>"
style="
display:inline-block;
margin-left:5px;
padding:12px 20px;
background:#eef7fa;
border-radius:6px;
text-decoration:none;
color:#263238;
"
>

Receive Stock

</a>


</form>

</div>


<?php

include "template_bottom.php";

$conn->close();

?>
