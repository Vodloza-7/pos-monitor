<?php

function writeAudit(
    $conn,
    $action,
    $description
) {

    if (!isset($_SESSION["monitor_user_id"])) {
        return;
    }


    $action =
        $conn->real_escape_string(
            $action
        );

    $conn->query(
        "INSERT INTO audittrail
        (
            `ACTION`,
            `DATE`,
            `TIME`
        )
        VALUES
        (
            '$action: $description',
            CURDATE(),
            CURTIME()
        )"
    );
}

?>