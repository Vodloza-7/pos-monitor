<?php
require_once "auth.php";
require_once "config.php";
$conn = monitor_db();

/*
 * We start by reading the sales table.
 * The POS itself remains unchanged.
 */
$sql = "SELECT * FROM sales ORDER BY ID DESC LIMIT 20";
$result = $conn->query($sql);

if (!$result) {
    die("Could not read sales: " . $conn->error);
}

$totalRecords = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>POS Remote Monitor</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #eef7fa;
            color: #263238;
        }

        .topbar {
            background: #bdebf3;
            border-bottom: 1px solid #9acfd8;
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand h1 {
            margin: 0;
            font-size: 21px;
        }

        .brand small {
            color: #53676b;
        }

        .status {
            background: white;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .navigation {
            background: white;
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            gap: 8px;
            overflow-x: auto;
        }

        .navigation a {
            text-decoration: none;
            color: #37474f;
            padding: 9px 14px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .navigation a.active {
            background: #d5f2f7;
            font-weight: bold;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 22px;
        }

        .welcome {
            margin-bottom: 20px;
        }

        .welcome h2 {
            margin-bottom: 5px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border-radius: 9px;
            padding: 18px;
            border: 1px solid #dde8eb;
        }

        .card small {
            color: #6b7d82;
        }

        .card h3 {
            margin: 9px 0 0;
            font-size: 25px;
        }

        .reports {
            margin-bottom: 25px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .report {
            background: #d9f1f5;
            padding: 14px;
            border-radius: 7px;
            border: 1px solid #bddfe5;
            cursor: pointer;
        }

        .report:hover {
            background: #c8e9ef;
        }

        .sales-box {
            background: white;
            border-radius: 9px;
            overflow: hidden;
            border: 1px solid #dde8eb;
        }

        .sales-header {
            padding: 16px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fbfc;
        }

        .sales-header h3 {
            margin: 0;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #ccecf2;
            text-align: left;
            padding: 11px;
            font-size: 13px;
        }

        td {
            padding: 10px 11px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        tr:hover {
            background: #f5fbfc;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #78909c;
            font-size: 12px;
        }

        @media (max-width: 800px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .report-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .topbar {
                align-items: flex-start;
                gap: 10px;
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 14px;
            }

            .cards {
                grid-template-columns: 1fr 1fr;
            }

            .report-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header class="topbar">

    <div class="brand">
        <h1>GLICARP INVESTMENTS</h1>
        <small>POS — Reliable & Robust</small>
    </div>

    <div class="status">
        ● Local POS Connected
    </div>

</header>


<nav class="navigation">

    <a href="index.php">Dashboard</a>
    <a href="sales.php">Sales</a>
    <a href="inventory.php">Inventory</a>
    <a href="suppliers.php">Suppliers</a>
    <a href="clients.php">Clients</a>
    <a href="reports.php">Reports</a>

</nav>


<main class="container">

    <section class="welcome">

        <h2>POS Remote Monitor</h2>

        <p>
            Monitor your store without being at the till.
        </p>

    </section>


    <section class="cards">

        <div class="card">
            <small>Recent Sales Loaded</small>
            <h3><?php echo $totalRecords; ?></h3>
        </div>

        <div class="card">
            <small>POS Status</small>
            <h3>Online</h3>
        </div>

        <div class="card">
            <small>Database</small>
            <h3>Connected</h3>
        </div>

        <div class="card">
            <small>Last Update</small>
            <h3><?php echo date("H:i"); ?></h3>
        </div>

    </section>


    <section class="reports">

        <h2>Sales Reports</h2>

        <div class="report-grid">

            <a class="report-card" href="hourly_sales.php">
        <strong>Hourly Sales</strong>
        </a>

        <a class="report-card" href="daily_sales.php">
        <strong>Itemized Daily Sales</strong>
        </a>

        <a class="report-card"
        href="under_development.php?report=Creditor Sales">
         <strong>Monitor Credit Sales</strong>
         </a>

        <a class="report-card" href="department_sales.php">
          <strong>Department Sales</strong>
         </a>

        <a class="report-card" href="transaction_summary.php">
            <strong>Transaction Summary</strong>
        </a>

        <a class="report-card" href="top_sellers.php">
           <strong>Top Sellers</strong>
        </a>
        <a class="report-card"
            href="under_development.php?report=Promotion Sales">
            <strong>Promotion Sales</strong>
        
         </a>

         <a class="report-card" href="sales_log.php">
            <strong>Sales Log</strong>
         </a>

          <a class="report-card" href="monthly_sales.php">
          <strong>Month-on-Month Sales</strong>
          </a>

          <a class="report-card"
              href="under_development.php?report=Client Payments">
             <strong>Monitor customer payments</strong>
          </a>

       </div>

    </section>


    <section class="sales-box">

        <div class="sales-header">

            <h3>Latest Sales</h3>

            <span>
                Live from local POS database
            </span>

        </div>


        <div class="table-wrapper">

            <table>

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

    </section>

</main>


<footer>

    POS Remote Monitoring Companion

</footer>


</body>
</html>

<?php
$conn->close();
?>