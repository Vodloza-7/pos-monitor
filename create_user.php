<?php

require_once "config.php";
$conn = monitor_db();

$username = "admin";

$password = "ChangeMe123!";

$fullName = "System Administrator";

$role = "ADMIN";


/* PHP 5.3 compatibility; upgrade XAMPP to use password_hash(). */
$salt =
    '$2a$10$' .
    substr(
        sha1(uniqid(mt_rand(), true)),
        0,
        22
    );

$hash = crypt($password, $salt);


$username =
    $conn->real_escape_string(
        $username
    );

$hash =
    $conn->real_escape_string(
        $hash
    );

$fullName =
    $conn->real_escape_string(
        $fullName
    );


$sql = "
INSERT INTO monitor_users
(
    USERNAME,
    PASSWORD_HASH,
    FULL_NAME,
    ROLE,
    ACTIVE
)
VALUES
(
    '$username',
    '$hash',
    '$fullName',
    '$role',
    1
)
";


if ($conn->query($sql)) {

    echo "Administrator created.";

} else {

    echo "Error: " .
         $conn->error;
}


$conn->close();

?>