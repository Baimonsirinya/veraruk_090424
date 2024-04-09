<?php

include "../connect.php";

$appointment_id = $_POST['appointment_id'];


$stmt = $pdo->prepare("UPDATE appointment_admin_page SET status = 'รอเรียกคิว' WHERE appointment_id = ?");
$stmt->execute([$appointment_id]);

// ตรวจสอบว่าอัปเดตสำเร็จหรือไม่
if ($stmt->rowCount() > 0) {
    echo 'success';
} else {
    echo 'error';
}

?>
