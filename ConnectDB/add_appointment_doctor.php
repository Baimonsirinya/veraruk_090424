<?php
include "../connect.php";
include "../session/sessionlogin.php";

if (isset($_POST['appointment_id'])) {
    $appointmentId = $_POST['appointment_id'];

    $stmt = $pdo->prepare("UPDATE appointment_admin_page SET status = 'เรียกพบแพทย์' WHERE appointment_id = :appointment_id");
    $stmt->bindParam(':appointment_id', $appointmentId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'เกิดข้อผิดพลาด';
}
?>
