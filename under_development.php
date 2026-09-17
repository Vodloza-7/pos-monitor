<?php

$page = "Reports";

include "template_top.php";

/* Get feature name from URL */
$feature = isset($_GET["feature"])
    ? $_GET["feature"]
    : "This Feature";

?>

<div style="
    max-width:650px;
    margin:70px auto;
    background:white;
    border:1px solid #dce8eb;
    border-radius:14px;
    padding:50px 30px;
    text-align:center;
    box-shadow:0 8px 25px rgba(0,0,0,0.06);
">

    <div style="
        width:80px;
        height:80px;
        margin:0 auto 20px;
        border-radius:50%;
        background:#e1f4f7;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:38px;
    ">
        🚧
    </div>

    <h1 style="
        margin:0 0 10px;
        color:#263238;
    ">
        <?php echo htmlspecialchars($feature); ?>
    </h1>

    <h3 style="
        color:#607d8b;
        font-weight:normal;
        margin-top:5px;
    ">
        Under Development
    </h3>

    <p style="
        color:#607d8b;
        line-height:1.7;
        max-width:480px;
        margin:20px auto;
    ">
        This feature is currently being integrated
        with the GLICARP INVESTMENTS POS.
        It will be available in a future update.
    </p>

    <div style="
        background:#f4fbfc;
        border-radius:8px;
        padding:14px;
        margin:25px 0;
        color:#455a64;
    ">
        Your existing POS continues to operate normally.
    </div>

    <a
        href="javascript:history.back()"
        style="
            display:inline-block;
            padding:12px 22px;
            background:#bdebf3;
            color:#263238;
            border-radius:7px;
            text-decoration:none;
            font-weight:bold;
        "
    >
        ← Go Back
    </a>

    <a
        href="index.php"
        style="
            display:inline-block;
            padding:12px 22px;
            margin-left:8px;
            background:#eef7fa;
            color:#263238;
            border-radius:7px;
            text-decoration:none;
        "
    >
        Dashboard
    </a>

</div>

<?php

include "template_bottom.php";

?>