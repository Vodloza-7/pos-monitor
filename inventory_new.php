<?php
require_once "auth.php";
require_once "audit.php";
require_once "config.php";

$conn = monitor_db();

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = "";
$error = "";


/* LOAD SUPPLIERS */

$suppliers = $conn->query(
    "SELECT SUPPLIER_ID, SUP_NAME, SUP_COMPANY
     FROM suppliers
     ORDER BY SUP_NAME ASC"
);


/* CREATE PRODUCT */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $plu = isset($_POST["plu"])
        ? $conn->real_escape_string(trim($_POST["plu"]))
        : "";

    $description = isset($_POST["description"])
        ? $conn->real_escape_string(trim($_POST["description"]))
        : "";

    $sellingPrice = isset($_POST["selling_price"])
        ? floatval($_POST["selling_price"])
        : 0;

    $costPrice = isset($_POST["cost_price"])
        ? floatval($_POST["cost_price"])
        : 0;

    $openingStock = isset($_POST["opening_stock"])
        ? floatval($_POST["opening_stock"])
        : 0;

    $taxStatus = isset($_POST["tax_status"])
        ? $conn->real_escape_string($_POST["tax_status"])
        : "NON-TAX";

    $department = isset($_POST["department"])
        ? $conn->real_escape_string(trim($_POST["department"]))
        : "";

    $supplierId = isset($_POST["supplier_id"])
        ? intval($_POST["supplier_id"])
        : 0;

    $safetyStock = isset($_POST["safety_stock"])
        ? floatval($_POST["safety_stock"])
        : 0;

    $reorderPoint = isset($_POST["reorder_point"])
        ? floatval($_POST["reorder_point"])
        : 0;

    $reorderQty = isset($_POST["reorder_qty"])
        ? floatval($_POST["reorder_qty"])
        : 0;

    $caseQty = isset($_POST["case_qty"])
        ? intval($_POST["case_qty"])
        : 1;

    $binLocation = isset($_POST["bin_location"])
        ? $conn->real_escape_string(trim($_POST["bin_location"]))
        : "";

    $weighed = isset($_POST["weighed"])
        ? 1
        : 0;


    if ($plu == "") {

        $error = "Stock Code is required.";

    } elseif ($description == "") {

        $error = "Description is required.";

    } elseif ($sellingPrice < 0 || $costPrice < 0) {

        $error = "Prices cannot be negative.";

    } else {

        /* CHECK DUPLICATE STOCK CODE */

        $check = $conn->query(
            "SELECT ID
             FROM inventory
             WHERE PLU = '$plu'
             LIMIT 1"
        );


        if ($check && $check->num_rows > 0) {

            $error =
                "Stock Code '$plu' already exists.";

        } else {

            /*
             * Create the inventory master record.
             *
             * These fields come from the inventory
             * structure you showed.
             */

            $sql = "
            INSERT INTO inventory
            (
                PLU,
                DESCRIPTION,
                SPRICE,
                CPRICE,
                AVG_PRICE,
                ONHAND,
                TAX_STATUS,
                DEPT_NAME,
                SUPPLIER_ID,
                BULKBREAK,
                WEIGHED,
                SAFETY_STOCK,
                REORDER_PT,
                REORDER_QTY,
                CASE_QTY,
                IS_CHILD,
                HAS_BOM,
                IS_SERIALIZED,
                HAS_ALT,
                BINLOCATION,
                LOCATION_ID,
                USER_PRICED,
                LAST_SOLD
            )
            VALUES
            (
                '$plu',
                '$description',
                $sellingPrice,
                $costPrice,
                $costPrice,
                $openingStock,
                '$taxStatus',
                '$department',
                $supplierId,
                0,
                $weighed,
                $safetyStock,
                $reorderPoint,
                $reorderQty,
                $caseQty,
                0,
                0,
                0,
                0,
                '$binLocation',
                0,
                0,
                ''
            )
            ";


            if ($conn->query($sql)) {

                $newId =
                    $conn->insert_id;


                /*
                 * inventorybb appears to represent
                 * case/bulk-break information.
                 *
                 * We only create it when case quantity
                 * is greater than 1.
                 */

                if ($caseQty > 1) {

                    $safeDescription =
                        $conn->real_escape_string(
                            $description
                        );

                    $conn->query(
                        "INSERT INTO inventorybb
                         (
                             P_ID,
                             DESCRIPTION,
                             CASE_QTY
                         )
                         VALUES
                         (
                             $newId,
                             '$safeDescription',
                             $caseQty
                         )"
                    );
                }


                $message =
                    "Product created successfully.";
                 writeAudit(
                    $conn,
                   "PRODUCT_CREATED",
                   $_SESSION["monitor_full_name"] .
                   " created a new product: " .
                   $description .
                   " (Stock Code " .
                  $plu .
                  ")."
                 );

            } else {

                $error =
                    "Unable to create product: " .
                    $conn->error;
            }
        }
    }
}


$page = "Inventory";

include "template_top.php";

?>


<h2>New Item Entry</h2>

<p class="subtitle">
Create a completely new product in the POS.
</p>


<?php if ($message != "") { ?>

<div style="
background:#e8f5e9;
padding:15px;
border-radius:7px;
margin-bottom:20px;
">

<strong>✓ Product Created</strong>

<br>

<?php echo htmlspecialchars($message); ?>

<br><br>

<a href="inventory.php">
Return to Inventory
</a>

</div>

<?php } ?>


<?php if ($error != "") { ?>

<div style="
background:#ffebee;
padding:15px;
border-radius:7px;
margin-bottom:20px;
">

<strong>Unable to Create Product</strong>

<br>

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


<div style="
display:grid;
grid-template-columns:
repeat(auto-fit,minmax(210px,1fr));
gap:16px;
">


<div>

<label>
<strong>Stock Code *</strong>
</label>

<input
type="text"
name="plu"
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
<strong>Description *</strong>
</label>

<input
type="text"
name="description"
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
<strong>Selling Price *</strong>
</label>

<input
type="text"
name="selling_price"
value="0.00"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Cost Each *</strong>
</label>

<input
type="text"
name="cost_price"
value="0.00"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div>

<label>
<strong>Opening Stock</strong>
</label>

<input
type="text"
name="opening_stock"
value="0"
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

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

<option value="NON-TAX">
NON-TAX
</option>

<option value="VAT">
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

?>

<option
value="<?php
echo intval(
    $supplier["SUPPLIER_ID"]
);
?>"
>

<?php

echo htmlspecialchars(
    $supplier["SUP_NAME"]
);

if (
    $supplier["SUP_COMPANY"] != ""
) {

    echo " - " .
    htmlspecialchars(
        $supplier["SUP_COMPANY"]
    );
}

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
value="0"
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
value="0"
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
value="0"
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
value="1"
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
style="
width:100%;
padding:10px;
margin-top:5px;
"
>

</div>


<div style="
display:flex;
align-items:center;
">

<label>

<input
type="checkbox"
name="weighed"
>

Weighed Item

</label>

</div>


</div>


<br>


<button
type="submit"
style="
background:#bdebf3;
border:1px solid #8fcbd5;
padding:12px 25px;
border-radius:6px;
font-weight:bold;
cursor:pointer;
"
>
Save Product
</button>


<a
href="inventory.php"
style="
margin-left:12px;
text-decoration:none;
"
>
Cancel
</a>


</form>

</div>


<?php

include "template_bottom.php";

$conn->close();

?>

