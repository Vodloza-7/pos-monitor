<?php

session_start();

require_once "config.php";
$conn = monitor_db();

$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username =
        isset($_POST["username"])
        ? trim($_POST["username"])
        : "";

    $password =
        isset($_POST["password"])
        ? $_POST["password"]
        : "";


    $safeUsername =
        $conn->real_escape_string(
            $username
        );


    $result =
        $conn->query(
            "SELECT
                ID,
                USERNAME,
                PASSWORD_HASH,
                FULL_NAME,
                ROLE
             FROM monitor_users
             WHERE USERNAME =
                 '$safeUsername'
             AND ACTIVE = 1
             LIMIT 1"
        );


    if (
        $result &&
        $result->num_rows == 1
    ) {

        $user =
            $result->fetch_assoc();


        if (
            crypt(
                $password,
                $user["PASSWORD_HASH"]
            ) === $user["PASSWORD_HASH"]
        ) {

            /*
             * Prevent session fixation.
             */

            session_regenerate_id(true);


            $_SESSION["monitor_user_id"] =
                intval($user["ID"]);

            $_SESSION["monitor_username"] =
                $user["USERNAME"];

            $_SESSION["monitor_full_name"] =
                $user["FULL_NAME"];

            $_SESSION["monitor_role"] =
                $user["ROLE"];


            header(
                "Location: index.php"
            );

            exit;

        } else {

            $error =
                "Incorrect username or password.";
        }

    } else {

        $error =
            "Incorrect username or password.";
    }
}

?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1"
>

<title>
Login | GLICARP POS Monitor
</title>


<style>

body {

    margin:0;

    min-height:100vh;

    display:flex;

    align-items:center;

    justify-content:center;

    font-family:Arial,sans-serif;

    background:#eef7fa;

}


.login-box {

    width:90%;

    max-width:400px;

    background:white;

    padding:35px;

    border-radius:12px;

    border:1px solid #dce8eb;

    box-shadow:
        0 10px 30px
        rgba(0,0,0,0.08);

}


.brand {

    text-align:center;

    margin-bottom:30px;

}


.brand h1 {

    margin:0;

    color:#263238;

}


.brand p {

    color:#607d8b;

}


label {

    font-weight:bold;

}


input {

    width:100%;

    box-sizing:border-box;

    padding:12px;

    margin-top:6px;

    margin-bottom:18px;

    border:1px solid #bbb;

    border-radius:6px;

}


button {

    width:100%;

    padding:13px;

    border:none;

    border-radius:6px;

    background:#bdebf3;

    color:#263238;

    font-weight:bold;

    cursor:pointer;

}


.error {

    background:#ffebee;

    border:1px solid #ef9a9a;

    padding:12px;

    border-radius:6px;

    margin-bottom:18px;

}


</style>

</head>


<body>


<div class="login-box">


<div class="brand">

<h1>
GLICARP INVESTMENTS
</h1>

<p>
POS Remote Monitor
</p>

</div>


<?php if ($error != "") { ?>

<div class="error">

<?php
echo htmlspecialchars($error);
?>

</div>

<?php } ?>


<form method="POST">


<label>
Username
</label>

<input
type="text"
name="username"
required
autocomplete="username"
>


<label>
Password
</label>

<input
type="password"
name="password"
required
autocomplete="current-password"
>


<button type="submit">

LOGIN

</button>


</form>


</div>


</body>

</html>


