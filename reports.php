<?php
require_once "auth.php";

$page = "Reports";

include "template_top.php";

?>

<h2>Reports</h2>

<p class="subtitle">
Monitor business performance remotely.
</p>

<div class="report-grid">

<a
class="report-card"
href="hourly_sales.php">

<strong>Hourly Sales</strong>

<p>
Sales performance by hour.
</p>

</a>


<a
class="report-card"
href="daily_sales.php">

<strong>Itemized Daily Sales</strong>

<p>
View today's sold items.
</p>

</a>


<a
class="report-card"
href="departmental_sales.php">

<strong>Department Sales</strong>

<p>
Sales grouped by department.
</p>

</a>


<a
class="report-card"
href="transaction_summary.php">

<strong>Transaction Summary</strong>

<p>
Summary of POS transactions.
</p>

</a>


<a
class="report-card"
href="top_sellers.php">

<strong>Top Sellers</strong>

<p>
See best-performing products.
</p>

</a>


<a
class="report-card"
href="sales_log.php">

<strong>Sales Log</strong>

<p>
Detailed transaction history.
</p>

</a>


<a
class="report-card"
href="monthly_sales.php">

<strong>Month-on-Month Sales</strong>

<p>
Compare monthly performance.
</p>

</a>


</div>

<?php

include "template_bottom.php";

?>