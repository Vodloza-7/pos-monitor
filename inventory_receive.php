
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


/* RECEIVE STOCK */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id =
        intval($_POST["product_id"]);

    $quantity =
        floatval($_POST["quantity"]);

    if ($quantity <= 0) {

        $error =
            "Quantity received must be greater than zero.";

    } else {

        $conn->query("START TRANSACTION");

        $check = $conn->query(
            "SELECT DESCRIPTION, ONHAND
             FROM inventory
             WHERE ID = $id
             FOR UPDATE"
        );

        if (!$check ||
            $check->num_rows == 0) {

            $conn->query("ROLLBACK");

            $error = "Product not found.";

        } else {

            $before =
                $check->fetch_assoc();

            $oldStock =
                floatval($before["ONHAND"]);

            $update =
                $conn->query(
                    "UPDATE inventory
                     SET ONHAND =
                         ONHAND + $quantity
                     WHERE ID = $id"
                );

            if (!$update) {

                $conn->query("ROLLBACK");

                $error =
                    "Stock update failed: " .
                    $conn->error;

            } else {

                $conn->query("COMMIT");
                writeAudit(
                  $conn,
                 "STOCK_RECEIVED",
                  $_SESSION["monitor_full_name"] .
                 " added " .
                 number_format($quantity, 3) .
                 " units of " .
                 $productDescription .
                ". Stock changed from " .
                number_format($oldStock, 3) .
               " to " .
               number_format($newStock, 3) .
               "."
               );

                $newStock =
                    $oldStock + $quantity;

                $message =
                    "Stock received successfully. " .
                    number_format($oldStock,3) .
                    " → " .
                    number_format($newStock,3);
            }
        }
    }
}


/* LOAD PRODUCT */

$productResult = $conn->query(
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


$page = "Inventory";

include "template_top.php";

?>


<h2>Receive Stock</h2>

<p class="subtitle">
Goods Received / Stock Control
</p>


<?php if ($message != "") { ?>

<div style="
background:#e8f5e9;
padding:15px;
border-radius:7px;
margin-bottom:15px;
">

✓ <?php echo htmlspecialchars($message); ?>

</div>

<?php } ?>


<?php if ($error != "") { ?>

<div style="
background:#ffebee;
padding:15px;
border-radius:7px;
margin-bottom:15px;
">

<?php echo htmlspecialchars($error); ?>

</div>

<?php } ?>


<div style="
background:white;
padding:22px;
border-radius:10px;
border:1px solid #dce8eb;
max-width:650px;
">

<h3>

<?php
echo htmlspecialchars(
    $product["DESCRIPTION"]
);
?>

</h3>


<p>

<strong>Stock Code:</strong>

<?php
echo htmlspecialchars(
    $product["PLU"]
);
?>

</p>


<p>

<strong>Current Stock:</strong>

<?php
echo number_format(
    floatval($product["ONHAND"]),
    3
);
?>

</p>


<p>

<strong>Unit Cost:</strong>

<?php
echo number_format(
    floatval($product["CPRICE"]),
    2
);
?>

</p>


<form
method="POST"
action="inventory_receive.php?id=<?php echo $id; ?>"
>

<input
type="hidden"
name="product_id"
value="<?php echo $id; ?>"
>


<label>
<strong>Quantity Received</strong>
</label>

<br>

<input
type="text"
name="quantity"
required
placeholder="0"
style="
width:100%;
padding:11px;
margin:7px 0 18px;
"
>


<button
type="submit"
style="
background:#bdebf3;
border:1px solid #8fcbd5;
padding:11px 20px;
border-radius:6px;
cursor:pointer;
"
>

Receive Stock

</button>


<a
href="inventory.php"
style="
margin-left:10px;
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

