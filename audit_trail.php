<?php
require_once "auth.php";
require_once "config.php";
$conn = monitor_db();

$result = $conn->query(
    "SELECT
          TRAIL_ID,
          `ACTION`,
          `DATE`,
          `TIME`
     FROM audittrail
      ORDER BY `DATE` DESC, `TIME` DESC, TRAIL_ID DESC"
);

if (!$result) {
    die("Audit trail error: " . $conn->error);
}

$page = "Audit Trail";

include "template_top.php";
?>

<div>
    <h2>Audit Trail</h2>
    <p class="subtitle">Activity recorded by the monitor system.</p>
</div>

<input
    class="search"
    id="search"
    type="text"
    placeholder="Search audit records..."
    onkeyup="searchTable()"
>

<div class="table-box">
    <table id="dataTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
<?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["DATE"]); ?></td>
                <td><?php echo htmlspecialchars($row["TIME"]); ?></td>
                <td><?php echo htmlspecialchars($row["ACTION"]); ?></td>
            </tr>
<?php } ?>
        </tbody>
    </table>
</div>

<?php
include "template_bottom.php";
$conn->close();
?>