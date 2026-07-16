<?php

function logActivity($conn, $user_id, $full_name, $module, $activity)
{
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO activity_logs (user_id, full_name, module, activity)
         VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $user_id,
        $full_name,
        $module,
        $activity
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

?>