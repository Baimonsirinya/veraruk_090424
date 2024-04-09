<?php
include "../connect.php";

// ตรวจสอบว่ามีการส่งค่า ID ของนัดหมายมาหรือไม่
if (isset($_POST['id'])) {
    // รับค่า ID ของนัดหมาย
    $appointment_id = $_POST['id'];

    // เตรียมคำสั่ง SQL สำหรับลบข้อมูลนัดหมาย
    $stmt = $pdo->prepare("DELETE FROM appointment_admin_page WHERE appointment_id = ?");
    
    // ทำการลบข้อมูลนัดหมาย
    $result = $stmt->execute([$appointment_id]);

    // ตรวจสอบว่าการลบสำเร็จหรือไม่
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "เกิดข้อผิดพลาด";
}
?>