<?php
    include "../connect.php";
    include "../session/sessionlogin.php";

    if (isset($_POST['treatmentId'])) {
        $treatment_id = $_POST['treatmentId'];

        // อัปเดต status ในฐานข้อมูล treatment เป็น "ทำรายการเสร็จสิ้น"
        $updateStatus = $pdo->prepare("UPDATE treatment SET status = 'ทำรายการเสร็จสิ้น' WHERE treatment_id = ?");
        $updateStatus->bindParam(1, $treatment_id, PDO::PARAM_INT);
        $updateStatus->execute();

        echo "บันทึกข้อมูลเสร็จสิ้นแล้ว";
    } else {
        echo "ไม่พบค่า treatment_id ที่ส่งมา";
    }
?>
