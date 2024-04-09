<?php
// เชื่อมต่อฐานข้อมูล
include "../connect.php";

if (isset($_POST['citizen_id'])) {
    $citizen_id = $_POST['citizen_id'];

    // ทำการลบผู้ใช้งานจากฐานข้อมูล
    $stmt = $pdo->prepare("DELETE FROM users WHERE citizen_id = :citizen_id");
    $stmt->bindParam(':citizen_id', $citizen_id, PDO::PARAM_STR);
    $stmt->execute();

    // ตรวจสอบว่าลบสำเร็จหรือไม่
    if ($stmt->rowCount() > 0) {
        echo "success";
    } else {
        echo "failed";
    }
}
?>
