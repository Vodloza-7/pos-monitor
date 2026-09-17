<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title><?php echo $page; ?> | POS Monitor</title>

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
    padding: 16px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand h2 {
    margin: 0;
}

.brand small {
    color: #52676b;
}

.status {
    background: white;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 12px;
}

nav {
    background: white;
    display: flex;
    padding: 10px 18px;
    gap: 5px;
    overflow-x: auto;
    border-bottom: 1px solid #ddd;
}

nav a {
    text-decoration: none;
    color: #37474f;
    padding: 10px 14px;
    border-radius: 6px;
    white-space: nowrap;
}

nav a:hover {
    background: #e2f5f8;
}

nav a.active {
    background: #ccecf2;
    font-weight: bold;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.subtitle {
    color: #607d8b;
}

.search {
    width: 100%;
    max-width: 450px;
    padding: 12px;
    margin: 10px 0 18px;
    border: 1px solid #bbb;
    border-radius: 6px;
}

.table-box {
    background: white;
    border-radius: 8px;
    overflow-x: auto;
    border: 1px solid #dce8eb;
}

table {
    border-collapse: collapse;
    width: 100%;
    min-width: 800px;
}

th {
    background: #ccecf2;
    padding: 11px;
    text-align: left;
    font-size: 13px;
}

td {
    padding: 10px 11px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}

tr:hover {
    background: #f4fbfc;
}

.cards {
    display: grid;
    grid-template-columns:
        repeat(auto-fit, minmax(180px,1fr));
    gap: 15px;
}

.card {
    background: white;
    padding: 18px;
    border-radius: 8px;
    border: 1px solid #dce8eb;
}

.card h3 {
    margin: 5px 0;
}

.report-grid {
    display: grid;
    grid-template-columns:
        repeat(auto-fit, minmax(180px,1fr));
    gap: 12px;
}

.report-card {
    background: #d7f0f4;
    padding: 18px;
    border-radius: 8px;
    text-decoration: none;
    color: #263238;
}

.report-card:hover {
    background: #c5e8ee;
}

.warning {
    background: #fff8e1;
    padding: 14px;
    border-radius: 7px;
    margin-bottom: 15px;
}

@media(max-width:600px) {

    .topbar {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }

    .container {
        padding: 14px;
    }

}

</style>

</head>

<body>

<header class="topbar">

<div class="brand">

<h2>GLICARP INVESTMENTS</h2>

<small>POS — Reliable & Robust</small>

</div>

<div class="status">
● POS Monitor
</div>

</header>

<nav>

<a
href="index.php"
class="<?php echo $page == 'Dashboard' ? 'active' : ''; ?>">
Dashboard
</a>

<a
href="sales.php"
class="<?php echo $page == 'Sales' ? 'active' : ''; ?>">
Sales
</a>

<a
href="inventory.php"
class="<?php echo $page == 'Inventory' ? 'active' : ''; ?>">
Inventory
</a>

<a
href="suppliers.php"
class="<?php echo $page == 'Suppliers' ? 'active' : ''; ?>">
Suppliers
</a>

<a
href="clients.php"
class="<?php echo $page == 'Clients' ? 'active' : ''; ?>">
Clients
</a>

<a
href="reports.php"
class="<?php echo $page == 'Reports' ? 'active' : ''; ?>">
Reports
</a>

</nav>

<main class="container">
<a href="audit_trail.php">
Audit Trail
</a>

<a href="logout.php">
Logout
</a>